<?php

namespace App\Repositories;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\MemberRejectionStatus;
use App\Models\MembershipType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice;

class MemberRepository extends Repository
{
    final public const MONTH_FOR_END_OF_FINANCIAL_YEAR = 6; // June.

    final public const DAYS_OF_MONTH_OF_FINANCIAL_YEAR = 30;

    /**
     * Get Members by Membership Status Id.
     *
     * @param  int  $limit - set to zero to remove limit
     */
    public function getByMembershipStatusId(int $membership_status_id, int $limit = 10): Collection
    {
        $query = Member::where('membership_status_id', $membership_status_id)
            ->latest();

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function generateEndDate(Carbon $current_dt = null)
    {
        if ($current_dt == null) {
            $current_dt = Carbon::now();
        }

        // Set end of financial year (June 30)
        $month = self::MONTH_FOR_END_OF_FINANCIAL_YEAR;
        $day = self::DAYS_OF_MONTH_OF_FINANCIAL_YEAR;

        if ($current_dt->month > $month) {
            $next_year = $current_dt->year + 1;

            return Carbon::create($next_year, $month, $day);
        }

        return Carbon::create($current_dt->year, $month, $day);
    }

    public function updateMembershipStatus(Member $member, MembershipStatus $status)
    {
        $member->membership_status_id = $status->value;
        $member->save();
    }

    public function accept(Member $member, User $user, int $financial_year = 0, string $receipt_number = '')
    {
        $this->updateMembershipStatus($member, MembershipStatus::ACCEPTED);

        if ($financial_year === 0) {
            $financial_year = Carbon::now()->year;
        }

        $to_date = $this->generateEndDate(
            Carbon::createFromDate(
                $financial_year + 1,
                self::MONTH_FOR_END_OF_FINANCIAL_YEAR,
                self::DAYS_OF_MONTH_OF_FINANCIAL_YEAR
            )
        );

        return $this->recordAction($member, $user, $to_date, $receipt_number);
    }

    public function reject(Member $member, string $reason)
    {
        $this->updateMembershipStatus($member, MembershipStatus::REJECTED);

        $member_rejected = new MemberRejectionStatus(['member_id' => $member->id, 'reason' => $reason]);
        $member_rejected->save();
    }

    public function markOptionalFlagAsViewed(Member $member, string $flag)
    {
        if ($flag == 'viewed_other_memberships') {
            $member->viewed_other_memberships = true;
            $member->save();
        } elseif ($flag == 'viewed_mailing_list') {
            $member->viewed_mailing_list = true;
            $member->save();
        }
    }

    public function recordAction(Member $member, User $user, $to_date = null, string $receipt_number = '')
    {
        $membership_status = new MemberMembershipStatus([
            'member_id' => $member->id,
            'membership_status_id' => $member->membership_status_id,
            'user_id' => $user->id,
            'from_date' => Carbon::now(),
            'receipt_number' => $receipt_number,
        ]);
        if ($to_date) {
            $membership_status->to_date = $to_date;
        }

        return $membership_status->save();
    }

    /**
     * Filter member list.
     *
     * @param  string  $search
     * @return Collection
     */
    public function filterMembers(array $membership_status_id, $search)
    {
        return Member::orderBy('first_name')
            ->where(function ($query) use ($membership_status_id, $search) {
                if ($membership_status_id !== [] && $search) {
                    $query->whereIn('membership_status_id', $membership_status_id);
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('job_title', 'like', '%'.$search.'%')
                            ->orWhere('current_employer', 'like', '%'.$search.'%');
                    });
                } elseif ($membership_status_id !== []) {
                    $query->whereIn('membership_status_id', $membership_status_id);
                } elseif ($search !== '' && $search !== '0') {
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('job_title', 'like', '%'.$search.'%')
                            ->orWhere('current_employer', 'like', '%'.$search.'%');
                    });
                }
            });
    }

    /**
     * Generate Invoice for membership subscription.
     *
     * @return \LaravelDaily\Invoices\Invoice
     */
    public function generateInvoice(Member $member)
    {
        $rep = new InvoiceSequenceRepository();
        $invoice_number = $rep->getNextInvoiceNumber();

        $customer = new Buyer([
            // Use home details.
            'name' => $member->first_name.' '.$member->last_name,
            // 'phone' => $member->home_mobile,
            'custom_fields' => [
                'Occupation' => $member->job_title.', '.$member->current_employer,
                'Email' => $member->home_email,
            ],
        ]);

        $membership_type = MembershipType::where('id', $member->membership_type_id)->first();
        $annual_cost = $membership_type->annual_cost;

        $item = (new InvoiceItem())->title('SITA Membership Subscription - '.$membership_type->title)
            ->pricePerUnit($annual_cost);

        $weeks = 4;
        $days_per_week = 7;
        $days_to_pay = $weeks * $days_per_week;

        $notes = [
            'Account name: Samoa Information Technology Association, Bank account number 2001364583, Swift code: BOSPWSWS.',
            '',
            'Bank name:',
            'Bank South Pacific (Samoa) Limited',
            '',
            'Bank Address:',
            'Head Office, Beach Road',
            'P.O Box 1860',
            'Apia, Samoa',
            '',
            'Please quote invoice number as reference',
            '',
            'Registered Office: Attention: Wellington Seufale, Private Bag,
                MOF, Central Bank Building, Floor 2, APIA, Samoa',
        ];
        $notes = '<br/>'.implode('<br/>', $notes);

        $invoice = Invoice::make()
            ->name('Invoice')
            ->date(Carbon::now())
            ->status('due')
            ->sequence($invoice_number)
            ->currencySymbol('$')
            ->currencyCode('Tala')
            ->dateFormat('d/m/Y')
            ->payUntilDays($days_to_pay)
            ->logo(public_path('imgs/logo.png'))
            ->filename('SITA Invoice '.$customer->name.' '.Carbon::now()->format('d-m-Y'))
            ->buyer($customer)
            ->notes($notes)
            ->addItem($item);

        $invoice->save('invoices');

        return $invoice;
    }
}
