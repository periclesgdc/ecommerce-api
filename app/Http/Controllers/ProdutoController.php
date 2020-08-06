<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use Validator;

class ProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.basic', ['except' => ['store']]);
    }

    public function index()
    {
        try {
            return response()->json(Produto::all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Nenhum registro encontrado',
            ], 404);
        }
    }

    public function show($id)
    {
        try {
            $produto = Produto::findOrFail($id);
            return response()->json($produto);
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
                'nome' => 'required|max:50',
                'preco' => 'required|regex:/\d+\.{0,1}\d*/i'
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

            $produto = new Produto();
            $produto->fill($data);
            $produto->save();

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
                'nome' => 'max:50',
                'preco' => 'regex:/\d+\.{0,1}\d*/i'
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

            $produto = Produto::findOrFail($id);
            $produto->fill($data);
            $produto->save();

            return response()->json([
                'message' => 'Registro atualizado com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não atualizado. Erro: '.$e->getMessage(),
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $produto = Produto::findOrFail($id);
            $produto->delete();

            return response()->json([
                'message' => 'Registro deletado com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não excluído',
            ], 422);
        }
    }

    public function listar()
    {
        return view('produto', ['produtos' => Produto::all()]);
    }

    public function alterar(Request $request, $id)
    {
        try {
            $produto = Produto::findOrFail($id);

            switch ($request->method()) {
                case 'GET':
                    $form = [
                        'action' => '/produtos/alterar/'.$produto->id,
                        'method' => 'POST',
                        'fields' => [
                            ['label' => 'Nome', 'name' => 'nome', 'type' => 'text', 'class' => 'form-control', 'value' => $produto->nome],
                            ['label' => 'Preço', 'name' => 'preco', 'type' => 'text', 'class' => 'form-control', 'value' => $produto->preco],
                            ['name' => 'id', 'type' => 'hidden', 'class' => '', 'value' => $produto->id],
                        ]
                    ];

                    return view('formModel', ['form' => $form, 'title' => 'Alterar Produto']);
                    break;
                
                case 'POST':
                    var_dump('chegou');
                    $produto->fill($request->all());
                    $produto->save();

                    return redirect('produtos')->with('success', 'Produto alterado com sucesso');
                    break;
                default:
                    throw new \Exception("Requisição inválida", 1);
                    break;
            }
        } catch (\Exception $e) {
            return redirect('produto')->with('error', $e->getMessage());
        }
    }

    public function deletar($id)
    {
        try {
            $produto = Produto::findOrFail($id);
            $produto->delete();

            return redirect('produtos')->with('success', 'Deletado com sucesso');
        } catch (\Exception $e) {
            return redirect('produtos')->with('error', $e->getMessage());
        }
    }
}
