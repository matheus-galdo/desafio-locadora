<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Falha ao criar token'], 500);
        }

        return response()->json(compact('token'));
    }

    /**
     * Create a new user with the given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $userDTO = new UserDTO(
            name: $request->name,
            email: $request->email,
            password: bcrypt($request->password),
        );

        $user = $this->userService->registerUser($userDTO);
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], 201);
    }

    public function user()
    {
        $user = auth()->user();
        return response()->json(compact('user'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
