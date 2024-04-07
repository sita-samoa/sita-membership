<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\MemberMailingPreference;

class MailingListRepository extends Repository
{
    public function getAllSubscribedMembers($id)
    {
        $member_preferences = MemberMailingPreference::where('mailing_list_id', $id)
            ->where('subscribed', true)->get('member_id');

        return Member::whereIn('id', $member_preferences);
    }

    public function getAllEmails($members): string
    {
        return implode('; ', $members->pluck('home_email')->map(fn ($item) => (string) $item)->toArray());
    }

    public function getSubStatusCount($id, $status, $fromDate, $tillDate)
    {
        return MemberMailingPreference::where('mailing_list_id', $id)->where('subscribed', $status)->
        where('updated_at', '>=', $fromDate)->where('updated_at', '<=', $tillDate)->count();
    }
}
