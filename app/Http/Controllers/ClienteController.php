<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use Validator;
use Hash;

class ClienteController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth', ['except' => ['index', 'show', 'store']]);
    }
    
    public function index()
    {
        try {
            return response()->json(Cliente::all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Nenhum registro encontrado',
            ], 404);
        }
    }

    public function show($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro com id('.$id.') não encontrado',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'nome' => 'required|max:200',
                'email' => 'required|max:100|regex:/^.+@.+$/i',
                'senha' => 'required|min:8|max:50',
                'telefone' => 'required|max:25',
                'endereco' => 'required|max:500'
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

            $cliente = new Cliente();
            $cliente->fill($data);
            $cliente->senha = Hash::make($data['senha']);
            $cliente->save();

            return response()->json([
                'message' => 'Registro salvo com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não persistido. Erro: '.$e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'nome' => 'max:200',
                'email' => 'max:100|regex:/^.+@.+$/i',
                'senha' => 'min:8|max:50',
                'telefone' => 'max:25',
                'endereco' => 'max:500'
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

            $cliente = Cliente::findOrFail($id);
            $cliente->fill($data);
            $cliente->senha = Hash::make($data['senha']);
            $cliente->save();

            return response()->json([
                'message' => 'Registro atualizado com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não atualizado. Erro: '.$e->getMessage(),
            ], 422);
        }
    }
}
