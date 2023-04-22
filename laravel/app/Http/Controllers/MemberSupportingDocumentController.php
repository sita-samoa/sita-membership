<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberSupportingDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberSupportingDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Member $member)
    {
        return $member->supportingDocuments()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('view', $member);

        $validated = $request->validate([
            'title' => 'required|string',
        ]);

        $member_supporting_document = $member->supportingDocuments()->create($validated);

        return redirect()->back()
            ->with('success', 'Supporting Document added.')
            ->with('data', [
                'id' => $member_supporting_document->id,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member, MemberSupportingDocument $document) : RedirectResponse
    {
        // @todo - Implement authorization here
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'required|string',
        ]);

        $document->update($validated);

        return redirect()->back()->with('success', 'Supporting Document updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member, MemberSupportingDocument $document) : RedirectResponse
    {
        // @todo - Implement authorization here
        $this->authorize('update', $document);

        $document->delete();

        return redirect()->back()->with('success', 'Supporting Document deleted.');
    }
}
