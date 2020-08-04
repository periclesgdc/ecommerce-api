<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pedido;
use App\PedidoProduto;
use Validator;

class PedidoController extends Controller
{
    public function index()
    {
        try {
            $pedidos = Pedido::with('produtos')->get();
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

            $validator = Validator::make($data, [
                'cliente_id' => 'required|regex:/^\d+$/i',
                'status_id' => 'required|regex:/^\d+$/i',
                'produtos' => ['required', 'regex:/^(?!\|)(\|?\d+)+$/']
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

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
                'message' => 'Registro não persistido. Erro: '.$e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            $validator = Validator::make($data, [
                //'cliente_id' => 'regex:/^\d+$/i',
                'status_id' => 'regex:/^\d+$/i',
                'produtos' => ['regex:/^(?!\|)(\|?\d+)+$/']
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

            $pedido = Pedido::findOrFail($id);
            $pedido->fill($data);
            $pedido->save();

            if (isset($data['produtos'])) {
                $pedidosProdutos = PedidoProduto::all()->where('pedido_id', $id);

                foreach ($pedidosProdutos as $obj) {
                    $obj->delete();
                }

                foreach (explode('|', $data['produtos']) as $produto_id) {
                    $pedidoProdutos = new PedidoProduto();
                    $pedidoProdutos->fill([
                        'pedido_id' => $pedido->id,
                        'produto_id' => $produto_id
                    ]);
                    $pedidoProdutos->save();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Registro atualizado com sucesso',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Registro não atualizado. Erro: '.$e->getMessage(),
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
