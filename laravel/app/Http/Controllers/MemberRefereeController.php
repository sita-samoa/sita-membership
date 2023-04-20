<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberReferee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberRefereeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Member $member)
    {
        return $member->referees()->get();
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
            'name' => 'required|string',
            'organisation' => 'required|string',
            'phone' => 'required|string|min:5',
            'email' => 'required|email|string'
        ]);

        $member->referees()->create($validated);

        return redirect()->back()->with('success', 'Referee added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberReferee $referee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberReferee $referee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberReferee $referee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberReferee $referee)
    {
        //
    }
}
