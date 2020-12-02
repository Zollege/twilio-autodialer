<?php

namespace App\Http\Controllers;

use App\Services\SocialAccountService;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        \Log::info('SocialAuthController->redirect() was invoked'); 
        return Socialite::driver($provider)->redirect();
    }

    public function callback(SocialAccountService $service, $provider)
    {
       
        \Log::info('SocialAuthController->callback() was invoked'); 
        $user = $service->createOrGetUser(Socialite::driver($provider));
        \Log::info('SocialAuthController->callback() user: '. $user); 

        auth()->login($user);

        return redirect()->to('/');
    }
}
