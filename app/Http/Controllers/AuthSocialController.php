<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialController extends Controller
{
    public function redirectTo(Request $request,$provider)
    {
        $request->validate(   [
            "redirect" => ['required', 'url']
        ]);

        try {
            $url = Socialite::driver($provider)
                ->stateless()
                ->redirectUrl($request->input('redirect'))->redirect()->getTargetUrl();
            return response()->json([
                'code' => 200,
                "message" => trans('api.success_operation'),
                "url" => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }


    public function handleCallback(Request $request,$provider)
   {
        $request->validate( [
            "redirect" => [Rule::requiredIf(empty(\request()->input('access_token')))],
            "code" => [Rule::requiredIf(empty(\request()->input('access_token')))],
            "access_token" => [Rule::requiredIf(empty(\request()->input('code')))]
        ]);
        try {
                if (!empty(\request()->input('access_token'))) {
                    $user = Socialite::driver($provider)
                        ->stateless()
                        ->userFromToken(\request()->input('access_token'));
                } else {
                    $response = Socialite::driver($provider)
                        ->stateless()
                        ->redirectUrl(request()->input('redirect'))
                        ->getAccessTokenResponse(request()->input('code'));

                    $user = Socialite::driver($provider)
                        ->stateless()
                        ->redirectUrl(request()->input('redirect'))
                        ->userFromToken($response['access_token']);
                }
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                "message" => $e->getMessage()
            ], 500);
        }
        $name = $user->getName();
        $email = $user->getEmail();
        $nickname = $user->getNickname();
        $driver = Driver::where('email',$email)->first();
        if(!$driver){
            $driver = Driver::create([
                "first_name"=>$name,
                "email"=>$email,
                "last_name"=>$nickname
                ]);
        }
         $token = $driver->createToken('driver_token')->plainTextToken;
        return response()->json(data: [
            "code" => 200,
            "message" => "login-successfully",
            'token' => $token,
            "item" =>  $driver
        ]);
    }
}
