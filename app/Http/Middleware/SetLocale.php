<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If user is logged in
        if (Auth::check()) {
            // Get the user specific language
            $lang = Auth::user()->language;
        
            // Set the language
            App::setLocale($lang); 
        }
       
        return $next($request);
    }
}