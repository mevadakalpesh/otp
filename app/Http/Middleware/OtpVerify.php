<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class OtpVerify
{
  /**
  * Handle an incoming request.
  *
  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
  */
  public function handle(Request $request, Closure $next): Response
  {
    if (Auth::check()) {
      if (Auth::user()->opt_verification == 1) {
        return $next($request);
      } else {
        return to_route('giveOtp');
      }
    }
    return to_route('login');
  }
}