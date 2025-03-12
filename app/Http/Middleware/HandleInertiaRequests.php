<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if (Auth::check())
        {
            $nav = [
                'Tracks' => 'track.index',
                'Map' => 'track.map',
                'Days Chart' => 'track.chart',
                'Upload Tracks' => 'track.create',
                'About' => 'about',
            ];
        }
        else
        {
            $nav = [
                'Register' => 'register',
                'Login' => 'login',
            ];
        }
        
        //"global" variables read by usePage in MainLayout.vue
        return array_merge(parent::share($request), [
            'success' => $request->session()->get('success'),
            'nav' => $nav,
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'units' => Auth::user()->units
            ] : null
        ]);
    }
}
