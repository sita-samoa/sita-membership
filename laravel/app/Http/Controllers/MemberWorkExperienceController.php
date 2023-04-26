<?php

namespace App\Http\Controllers;

use App\Models\MemberWorkExperience;
use Illuminate\Http\Request;

class MemberWorkExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $attributes = request()->validate([
            'member_id' => 'required|numeric',
            'organisation' => 'required|max:255',
            'position' => 'required|max:255',
            'responsibilities' => 'required|max:255',
            'from_date' => 'required|date|before:to_date',
            'to_date' => 'required|date',
        ]);

        MemberWorkExperience::create($attributes);

        return redirect()->back()
            ->with('success', 'Work experience added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberWorkExperience $memberWorkExperience)
    {
        $attributes = request()->validate([
            'member_id' => ['required', 'numeric'],
            'organisation' => ['required', 'max:255'],
            'position' => ['required', 'max:255'],
            'responsibilities' => ['required', 'max:255'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date'],
        ]);

        $memberWorkExperience->update($attributes);

        return redirect()->back()
            ->with('success', 'Work experience saved.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberWorkExperience $memberWorkExperience)
    {
        $memberWorkExperience->delete();
        return redirect()->back()
            ->with('success', 'Work experience deleted.');
    }
}
