<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/login",
     * summary="Sign in",
     * description="Login by login, password",
     * operationId="authLogin",
     * tags={"login"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"login","password"},
     *       @OA\Property(property="login", type="string"),
     *       @OA\Property(property="password", type="string", format="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="correct credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="token", type="string", example="success")
     *        ),
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorised"),
     *        ),
     *     ),
     * ),
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'login'=>'required',
            'password'=>'required'
        ]);

        $user = User::query()->where('login','=',$request->login)->first();
        if($user)
        {
            if(Hash::check($request->password, $user->password)){  //Hash::check($request->password, $user->password
                $token = $user->createToken('PassportToken')->accessToken;
                return response()->json(['token' => $token, 'success'=>true], 200);
            }else{
                return response()->json(['error' => 'Unauthorised', 'success'=>false], 401);
            }
        }else{
            return response()->json(['error' => 'Unauthorised', 'success'=>false], 401);
        }
    }
}
