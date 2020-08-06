<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use Validator;
use Hash;
use JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['email', 'senha']);

        $validator = Validator::make($credentials, [
        	'email' => 'required|max:100|regex:/^.+@.+$/i',
        	'senha' => 'required|min:8|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
            	'message' => 'Erro de validação',
            	'errors' => $validator->errors()->all()
            ], 401);
        }

        $cliente = Cliente::where('email', $credentials['email'])->first();

        if(!$cliente) {
            return response()->json([
                'error' => 'Não há usuário encontrado com este login: Email ('.$credentials['email'].')',
            ], 401);
        }

        if (!Hash::check($credentials['senha'], $cliente->senha)) {
            return response()->json([
                'error' => 'A senha não confere para o usuário: Senha ('.$credentials['senha'].')',
            ], 401);
        }

        $token = JWTAuth::fromUser($cliente);

        if (!$token) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Logoff feito com sucesso']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
