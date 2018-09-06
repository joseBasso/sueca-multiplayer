<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;

define('YOUR_SERVER_URL', 'http://sueca.proj');
define('CLIENT_ID', '2');

define('CLIENT_SECRET','Di6POzLXY72I92Uh1EVD4ShSIVNwyPsrg3kIeNg2');


class LoginControllerAPI extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
        ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            $user = User::byIdentifier($request->email);
            if ($user->blocked == 1) {
                return response()->json(
                    ['message'=>'You are blocked, please check your email for further details'], 401);
            }
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(
                ['message'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }

    public function loginAdmin(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
        ]);

        $errorCode= $response->getStatusCode();
        $token = json_decode((string) $response->getBody(),true);
        $user = User::where('email','=',$request->email)->first();
        if ($errorCode=='200') {
            if ($user->admin == 0) {
                return response()->json(
                    ['message'=>'User not admin'], 401);
            }
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(
                ['message'=>'User credentials are invalid'], $errorCode);
        }
    }
}
