<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Member::class);

        return Inertia::render('Users/Index', [
            'filters' => $request->all('membership_status_id'),
            'users' => User::orderBy('name')
                // ->when(
                //     $request->input('membership_status_id'),
                //     function ($query) {
                //         $query->where(FacadesRequest::only('membership_status_id'));
                //     }
                // )
                // ->with('membershipType', 'title', 'membershipStatus')
                ->paginate(10)
                ->withQueryString(),
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // @todo - Add user policy

        $validated = $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            // 'owner' => ['required', 'boolean'],
            // 'photo' => ['nullable', 'image'],
        ]);

        $user->update($validated);

        if ($request->file('photo')) {
            $user->update(['photo_path' => $request->file('photo')->store('users')]);
        }

        if ($request->get('password')) {
            $user->update(['password' => $request->get('password')]);
        }

        return redirect()->back()->with('success', 'User updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
