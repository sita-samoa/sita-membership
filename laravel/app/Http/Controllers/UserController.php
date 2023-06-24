<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class UserController extends Controller
{
    use PasswordValidationRules;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $search = $request->input('search');
        $role = $request->input('role');

        $ids_only = [];
        if ($role) {
            $team = Team::first();
            $ids = $team->users()->where('role', $role)->get(['user_id']);
            $ids_only = array_column($ids->all(), 'user_id');
        }

        return Inertia::render('Users/Index', [
            'filters' => $request->all('search', 'role'),
            'users' => User::orderBy('name')
                ->when(
                    $role,
                    fn ($query) => $query->whereIn('id', $ids_only)
                )
                ->when(
                    $search,
                    fn ($query) => $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                )
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
        $input = array_merge(
            $request->only('name', 'email', 'password', 'photo'),
            [
                'password_confirmation' => $request->input('password'),
            ]
        );

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'photo' => 'nullable|image',
        ];

        $validated = Validator::make($input, $rules)->validate();

        DB::transaction(function () use ($validated, $request) {
            return tap(User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'photo_path' => $request->file('photo') ? $request->file('photo')->store('users') : null,
            ]), function (User $user) {
                $user->ownedTeams()->save(Team::forceCreate([
                    'user_id' => $user->id,
                    'name' => explode(' ', $user->name, 2)[0] . "'s Team",
                    'personal_team' => true,
                ]));
            });
        });

        // $input = array_merge($request->only('name', 'email', 'password'), [
        //     'password_confirmation' => $request->input('password'),
        //     'terms' => 'accepted',
        // ]);
        // $newUser->create($input);

        // $validated = $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => $this->passwordRules(),
        //     'photo' => 'nullable|image',
        // ]);

        // $user = new User();
        // $user->fill($validated);

        // $user->password = Hash::make($validated['password']);
        // $user->password_confirmation = Hash::make($validated['password']);
        // $user->photo_path = $request->file('photo') ? $request->file('photo')->store('users') : null;
        // $user->save();

        return redirect()->back()->with('success', 'User created.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $rep = new UpdateUserProfileInformation();

        $rep->update($user, $request->only(['name', 'email', 'photo']));

        if ($request->input('password')) {
            $input = $request->only('password');

            // Make ResetUserPassword class happy and mock a confirmation
            $input = array_merge($input, [
                'password_confirmation' => $request->input('password'),
            ]);
            $reset = new ResetUserPassword();
            $reset->reset($user, $input);
        }

        if ($role = $request->input('role')) {
            // if its -1 remove it
            // if its the same dont do anything
            if (isset($user->role['key']) && $role === $user->role['key']) {
                // do nothing
            } else {
                // its changed
                $team = Team::first();
                if ($user->belongsToTeam($team)) {
                    $team->removeUser($user);
                }
                if ($role !== '-1') {
                    $user->teams()->attach($team, $request->only(['role']));
                }
            }
        }

        return redirect()->back()->with('success', 'User updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted.');
    }
}
