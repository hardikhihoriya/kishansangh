<?php

namespace App\Listeners;

use App\Events\UserRegistrationMailEvent;
use App\Events\UserRegistrationMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Config;

class UserRegistrationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistrationMailEvent  $event
     * @return void
     */
    public function userRegistrationMail(UserRegistrationMailEvent $event) {
        
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistrationMessageEvent  $event
     * @return void
     */
    public function userRegistrationMessage(UserRegistrationMessageEvent $event) {
        try {
            // Account details
            $apiKey = urlencode(Config::get('constant.MESSAGE_API_KEY'));

            // Message details
            $message = rawurlencode("Hello {$event->user->first_name}. Password for Khedut Pay account email ({$event->user->email}) is {$event->password}.");

            $numbers = "91{$event->user->phone_no}";

            // Prepare data for POST request
            $data = array('apikey' => $apiKey, 'numbers' => $numbers, "message" => $message);

            // Send the POST request with cURL
            $ch = curl_init('https://api.textlocal.in/send/');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            Log::info("Error while sendig message to user with #{$event->user->id}");
        }
    }
    
}
