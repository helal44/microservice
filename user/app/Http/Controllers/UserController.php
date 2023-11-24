<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
/**
 * @OA\Info(
 *      title="MyApp",
 *      version="1.0.0",
 *      description=" Register Yousre",
 *      @OA\Contact(
 *          email="helal@email.com",
 *          name=" helal"
 *      )  )
 * )
 * @group Authentication
 *
 * APIs for managing user authentication.
 */

class UserController extends Controller
{

    /**
     * Register a new user.
     *
     * @OA\Post(
     *      path="/register",
     *      summary="Register a new user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="helal "),
     *              @OA\Property(property="email", type="string", format="email", example="helal@example.com"),
     *              @OA\Property(property="password", type="string", example="helal123"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Successful registration",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="access_token"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Validation error or bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation failed"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal Server Error"),
     *          )
     *      )
     * )
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
      
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
           
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $token = JWTAuth::fromUser($user);
            return response()->json(['token' => $token], 200);

        } catch (\Exception $e) {
           
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }



    /**
     * Log in a user.
     *
     * @OA\Post(
     *      path="/login",
     *      summary="Log in a user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", format="email", example="helal@example.com"),
     *              @OA\Property(property="password", type="string", example="helal123"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Successful login",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="access_token"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Unauthorized"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal Server Error"),
     *          )
     *      )
     * )
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function login(Request $request)
    {
      
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);

       
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
           
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials)) {
              
                // $token = auth()->user()->createToken('MyApp')->accessToken;
                $token = JWTAuth::attempt($credentials);

              
                return response()->json(['token' => $token], 200);
            } else {
              
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}






