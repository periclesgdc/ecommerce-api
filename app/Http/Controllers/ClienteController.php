<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use Validator;
use Hash;

class ClienteController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth', ['except' => ['index', 'show', 'store', 'listar', 'alterar', 'deletar']]);
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

    public function listar()
    {
        $clientes = Cliente::all();

        $table['headers'] = ['Nome', 'Email', 'Endereço', 'Telefone', 'Ações'];

        foreach ($clientes as $key => $cliente) {
            $table['rows'][$key]['fields'] = [
                $cliente->nome,
                $cliente->email,
                $cliente->endereco,
                $cliente->telefone
            ];

            $table['rows'][$key]['actions']['changeURI'] = '/clientes/alterar/'.$cliente->id;
            $table['rows'][$key]['actions']['deleteURI'] = '/clientes/deletar/'.$cliente->id;
        }

        return view('listModel', ['table' => $table, 'title' => 'Clientes']);
    }

    public function alterar(Request $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);

            switch ($request->method()) {
                case 'GET':
                    $form = [
                        'action' => '/clientes/alterar/'.$cliente->id,
                        'method' => 'POST',
                        'fields' => [
                            ['label' => 'Nome', 'name' => 'nome', 'type' => 'text', 'class' => 'form-control', 'value' => $cliente->nome],
                            ['label' => 'Email', 'name' => 'email', 'type' => 'text', 'class' => 'form-control', 'value' => $cliente->email],
                            ['label' => 'Endereço', 'name' => 'endereco', 'type' => 'text', 'class' => 'form-control', 'value' => $cliente->endereco],
                            ['label' => 'Telefone', 'name' => 'Telefone', 'type' => 'text', 'class' => 'form-control', 'value' => $cliente->telefone],
                            ['name' => 'id', 'type' => 'hidden', 'class' => '', 'value' => $cliente->id],
                        ]
                    ];

                    return view('formModel', ['form' => $form, 'title' => 'Alterar Cliente']);
                    break;
                
                case 'POST':
                    $cliente->fill($request->all());
                    $cliente->save();

                    return redirect('clientes')->with('success', 'Cliente alterado com sucesso');
                    break;
                default:
                    throw new \Exception("Requisição inválida", 1);
                    break;
            }
        } catch (\Exception $e) {
            return redirect('clientes')->with('error', $e->getMessage());
        }
    }

    public function deletar($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            return redirect('clientes')->with('success', 'Deletado com sucesso');
        } catch (\Exception $e) {
            return redirect('clientes')->with('error', $e->getMessage());
        }
    }
}
