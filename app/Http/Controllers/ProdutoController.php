<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;

class ProdutoController extends Controller
{
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
            $produto = new Produto();
            $produto->fill($data);
            $produto->save();

            return response()->json([
                'message' => 'Registro salvo com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não persistido',
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $produto = Produto::findOrFail($id);
            $produto->fill($request->all());
            $produto->save();

            return response()->json([
                'message' => 'Registro atualizado com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não atualizado',
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $produto = Produto::find($id);
            $produto->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não excluído',
            ], 404);
        }
    }
}
