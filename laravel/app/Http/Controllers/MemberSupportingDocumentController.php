<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberSupportingDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

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
            'file' => [
                'required',
                File::types(['doc', 'docx', 'pdf', 'png', 'jpg'])
                    // ->min(1024)
                    // ->max(12 * 1024),
            ],
        ]);

        $member_supporting_document = new MemberSupportingDocument();
        $member_supporting_document->fill($validated);
        $member_supporting_document->member_id = $member->id;
        $path = $request->file('file') ? $request->file('file')->store('supportingDocuments') : null;
        $member_supporting_document->file_path = $path;
        $member_supporting_document->file_size = Storage::size($path);
        $member_supporting_document->save();

        // @todo do this on a queue for orphaneded files
        // Storage::delete('file.jpg');

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
            'file' => [
                'required',
                File::types(['doc', 'docx', 'pdf', 'png', 'jpg'])
                    // ->min(1024)
                    // ->max(12 * 1024),
            ],
        ]);

        $document->update($validated);

        if ($request->file('file')) {
            $document->update(['file_path' => $request->file('file')->store('supportingDocuments')]);
        }

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
