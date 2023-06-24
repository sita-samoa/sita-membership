<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

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
            'availableRoles' => array_values(Jetstream::$roles),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // @todo - Add user policy

        $rep = new UpdateUserProfileInformation();

        $rep->update($user, $request->only(['name', 'email', 'photo']));

        if ($request->input('password')) {
            $user->password = $request->input('password');
            $user->save();
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