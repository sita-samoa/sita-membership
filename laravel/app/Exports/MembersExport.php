<?php

namespace App\Exports;

use App\Models\Member;
use App\Repositories\MemberRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersExport implements FromCollection, WithMapping, WithHeadings
{
    public function __construct(
        public readonly string $member_status_id,
        public readonly string $search
    ) {
    }

    public function headings(): array
    {
        return [
            'id',
            'membership_status',
            'membership_type',
            'title',
            'first_name',
            'last_name',
            'dob',
            'gender',
            'job_title',
            'current_employer',
            'home_address',
            'home_phone',
            'home_mobile',
            'home_email',
            'work_address',
            'work_phone',
            'work_mobile',
            'work_email',
            'other_membership',
            'note',
            'created_at',
            'updated_at',
            'added_by_id',
        ];
    }

    /**
     * @var Member
     */
    public function map($member): array
    {
        return [
            $member->id,
            $member->membershipStatus ? $member->membershipStatus->title : '',
            $member->membershipType ? $member->membershipType->title : '',
            $member->title ? $member->title->title : '',
            $member->first_name,
            $member->last_name,
            $member->dob,
            $member->gender ? $member->gender->title : '',
            $member->job_title,
            $member->current_employer,
            $member->home_address,
            $member->home_phone,
            $member->home_mobile,
            $member->home_email,
            $member->work_address,
            $member->work_phone,
            $member->work_mobile,
            $member->work_email,
            $member->other_membership,
            $member->note,
            $member->created_at,
            $member->updated_at,
            $member->user ? $member->user->name.' ('.$member->user_id.')' : '',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rep = new MemberRepository();
        $status_ids = $this->member_status_id ? [$this->member_status_id] : [];

        return $rep->filterMembers($status_ids, $this->search)->get();
    }
}
