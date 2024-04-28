<?php

namespace App\Http\Middleware;

use App\Models\UserPermissions;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UserTypesAllowedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {
        $userType = auth()->user()->user_type_id;
        $currentRouteName = Route::currentRouteName();

        if ($currentRouteName != null) {
            if (UserPermissions::where([
                'user_type_id' => $userType,
                'route_name' => $currentRouteName,
                'cancelled' => 0
            ])->count()) {
                return $next($request);
            }

            abort(403, __('You Are Not Allowed To Access This Page.'));
        }
    }
}
