<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\MemberWorkExperience;
use App\Models\Title;
use Inertia\Inertia;
use Inertia\Response;

class SignupController extends Controller
{
    /**
     * Show the form for sign up of new member.
     */
    public function index(Member $member): Response
    {
        $this->authorize('view', $member);

        return Inertia::render('Members/Signup', [
            'member' => $member,
            'options' => [
                'membership_type_options' => MembershipType::all(['id', 'code', 'title']),
                'gender_options' => Gender::all(['id', 'code', 'title']),
                'title_options' => Title::all(['id', 'code', 'title']),
            ],
            'qualifications' => $member->qualifications()->get(),
            'referees' => $member->referees()->get(),
            'memberWorkExperiences' => MemberWorkExperience::select('id', 'organisation', 'position', 'responsibilities', 'from_date', 'to_date')->where('member_id', $member->id)->get(),
            'supportingDocuments' => $member->supportingDocuments()
                ->where('to_delete', false)
                ->get(['id', 'title', 'file_name', 'file_size']),
        ]);
    }
}
