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
    public function store(Member $member, Request $request): RedirectResponse
    {
        $this->authorize('view', $member);

        $file_upload_limit = env('SITA_FILE_UPLOAD_LIMIT') ?? 20;

        $validated = $request->validate([
            'title' => 'nullable|string',
            'file' => [
                'required',
                File::types(['doc', 'docx', 'pdf', 'png', 'jpg'])
                    // ->min(1024)
                    ->max($file_upload_limit * 1024),
            ],
        ]);
        $file = $request->file('file');
        $path = $file ? $file->store('supportingDocuments') : null;

        $document = new MemberSupportingDocument();
        $document->fill($validated);
        $document->member_id = $member->id;
        $document->file_name = $file ? $file->getClientOriginalName() : null;
        $document->file_path = $path;
        $document->file_size = $path ? Storage::size($path) : null;
        $document->save();

        return redirect()->back()
            ->with('success', 'Supporting Document added.')
            ->with('data', [
                'id' => $document->id,
                'file_name' => $document->file_name,
                'file_size' => $document->file_size,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member, MemberSupportingDocument $document): RedirectResponse
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'nullable|string',
            'file' => [
                'nullable',
                File::types(['doc', 'docx', 'pdf', 'png', 'jpg'])
                    // ->min(1024)
                    ->max(20 * 1024),
            ],
        ]);

        $document->update($validated);

        $file = $request->file('file');

        if ($file) {
            $path = $file ? $file->store('supportingDocuments') : null;

            $document->file_name = $file ? $file->getClientOriginalName() : null;
            $document->file_path = $path;
            $document->file_size = $path ? Storage::size($path) : null;
            $document->save();
        }

        return redirect()->back()
            ->with('data', [
                'file_name' => $document->file_name,
                'file_size' => $document->file_size,
            ])
            ->with('success', 'Supporting Document updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member, MemberSupportingDocument $document): RedirectResponse
    {
        $this->authorize('update', $document);

        // Dont delete the document but mark as deleted so that it
        //   can be deleted with the file during a background process @todo.
        // Storage::delete('file.jpg');
        $document->to_delete = true;
        $document->save();

        return redirect()->back()->with('success', 'Supporting Document deleted.');
    }
}
