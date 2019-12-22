<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\User;

class AuthorizeAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('users') && auth()->user()->type != 'a')
        {
            return redirect('/home');
        }
        else
            return $next($request);

    }
}
