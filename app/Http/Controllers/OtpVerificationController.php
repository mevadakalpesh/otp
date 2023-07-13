<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\SendOtpEvent;

class OtpVerificationController extends Controller
{
  public function giveOtp() {
    $otpData =Otp::selectRaw('user_id,expires_at')->where('user_id', Auth::user()->id)->first();

    return view('auth/otp-verification', [
      'otpData' => $otpData
    ]);
  }

  public function submitOtp(Request $request) {

    $otp = $request->otp;
    $otpData = Otp::where('user_id', Auth::user()->id)->where('otp', $otp)->first();
    if (!$otpData) {
      return redirect()->back()->withError('otp is wrong');
    }

    $currentTime = Carbon::now()->timezone('Asia/Kolkata');
    $expires_at =
    Carbon::parse($otpData->expires_at);
    

    if ($expires_at->gte($currentTime)) {
      User::where('id', Auth::user()->id)->update(['opt_verification' => 1]);
      return to_route('home');
    } else {
      return redirect()->back()->withError('otp is wrong');
    }
  }


  public function resendOtp(Request $request) {
    $user = Auth::user();
    event(new SendOtpEvent($user));
    $otpData = Otp::selectRaw('user_id,expires_at')->where('user_id',$user->id)->first();
    return response()->json(['code' => 200,'message' => 'Otp Sent
    Successdully','result' =>$otpData ]);
  }


}