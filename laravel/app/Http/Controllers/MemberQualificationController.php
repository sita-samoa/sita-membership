<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberQualification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberQualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Member $member)
    {
        return $member->qualifications()->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Member $member, Request $request) : RedirectResponse
    {
        $validated = $request->validate([
            'qualification' => 'required|string',
            'year_attained' => 'required|int|min:1900|max:3000',
            'institution' => 'required|string',
            // 'country_id' => 'required|string',
        ]);

        $member->qualifications()->create($validated);

        return redirect()->back()->with('success', 'Qualification added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberQualification $memberQualification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberQualification $memberQualification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberQualification $memberQualification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberQualification $memberQualification)
    {
        //
    }
}
