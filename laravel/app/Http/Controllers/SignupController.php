<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Member;
use App\Models\MemberQualification;
use App\Models\MembershipType;
use App\Models\Title;
use Inertia\Response;
use Inertia\Inertia;

class SignupController extends Controller
{
    /**
     * Show the form for sign up of new member.
     */
    public function index(Member $member) : Response
    {
        return Inertia::render('Members/Signup', [
            'member_id' => $member->id,
            'options' => [
                'membership_type_options' => MembershipType::all(['id', 'code', 'title']),
                'gender_options' => Gender::all(['id', 'code', 'title']),
                'title_options' => Title::all(['id', 'code', 'title']),
            ],
            'qualifications' => $member->qualifications()->get()
        ]);
    }

}
