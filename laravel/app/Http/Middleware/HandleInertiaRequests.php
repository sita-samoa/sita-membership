<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => function () use ($request) {
                return [
                    // custom flash objects for displaying alert messages
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    // custom flash object for passing data back to inertia
                    'data' => $request->session()->get('data'),
                ];
            },
            'user' => function () use ($request) {
                if (! $user = $request->user()) {
                    return;
                }

                return [
                    'member_id' => $user->members()->first()->id ?? 0,
                    'completion' => $user->members()->first() ? $user->members()->first()->completions : [],
                    'permissions' => $user->permissions ?? [],
                ];
            },
            'recaptcha_site_key' => config('services.google_recaptcha.site_key'),
        ]);
    }
}
