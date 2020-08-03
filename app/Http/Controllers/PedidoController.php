<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pedido;
use App\PedidoProduto;

class PedidoController extends Controller
{
    public function index()
    {
        try {
            $pedidos = Pedido::with(['cliente_id', 'status_id'])->get();
            return response()->json($pedidos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Nenhum registro encontrado',
            ], 404);
        }
    }

    public function show($id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            return response()->json($pedido);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro com id('.$id.') não encontrado',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $pedido = new Pedido();
            $pedido->fill($data);
            $pedido->save();

            foreach (explode('|', $data['produtos']) as $produto_id) {
                $pedidoProdutos = new PedidoProduto();
                $pedidoProdutos->fill([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $produto_id
                ]);
                $pedidoProdutos->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Registro salvo com sucesso',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Registro não persistido',
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->fill($request->all());
            $pedido->save();

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
            $pedido = Pedido::findOrFail($id);
            $pedido->delete();

            return response()->json([
                'message' => 'Registro deletado com sucesso',
            ], 201);
        } catch (\Exception $e) {
            var_dump($e);
            return response()->json([
                'message' => 'Registro não excluído',
            ], 422);
        }
    }
}
