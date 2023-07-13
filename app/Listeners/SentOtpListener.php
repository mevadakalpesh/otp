<?php

namespace App\Listeners;

use App\Events\SendOtpEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;
use App\Mail\SentOtpMail;

use Illuminate\Support\Facades\Mail;

class SentOtpListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendOtpEvent $event): void
    {
        $user = $event->user;
        $otp = rand(1,9999);
        $expires_at = Carbon::now()->addMinutes(5)->timezone('Asia/Kolkata')->toDateTimeString();
        
        Otp::updateOrCreate(['user_id' => $user->id],[
          'user_id' => $user->id,
          'otp' => $otp,
          'expires_at' =>$expires_at
        ]);
        
        
        
        Mail::to($user->email)->send(new SentOtpMail($user,$otp));
    }
    
    
  
}
