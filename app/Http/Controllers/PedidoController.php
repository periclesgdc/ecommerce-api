<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pedido;
use App\PedidoProduto;
use Validator;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'listar']);
    }

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
                'produtos' => ['required', 'regex:/^(?!\|)(\|?\d+)+$/']
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

            $pedido = new Pedido();
            $pedido->fill($data);
            $pedido->cliente_id = \Auth::user()->id;
            $pedido->status_id = env('STATUS_ID_PEDIDO_DEFAULT');
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
                //'status_id' => 'regex:/^\d+$/i',
                'produtos' => ['regex:/^(?!\|)(\|?\d+)+$/']
            ]);

            if ($validator->fails()) {
                throw new \Exception("Erro de validação", 1);
            }

            $pedido = Pedido::findOrFail($id);

            if(\Auth::user()->id != $pedido->cliente_id) {
                throw new \Exception("Você não tem permissão para alterar o pedido", 1);
            }

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

            if(\Auth::user()->id != $pedido->cliente_id) {
                throw new \Exception("Você não tem permissão para exluir o pedido", 1);
            }

            $pedido->delete();

            return response()->json([
                'message' => 'Registro deletado com sucesso',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro não excluído. Erro: '.$e->getMessage(),
            ], 422);
        }
    }

    public function listar()
    {
        //$pedidos = DB::table('tb_pedidos')
          //  ->join('tb_status', 'tb_pedidos.status_id', '=', 'tb_status.id')
            //->join('tb_clientes', 'tb_pedidos.cliente_id', '=', 'tb_clientes.id')
            //->select('tb_pedidos.*', 'tb_clientes.nome as cliente', 'tb_status.descricao as status')
            //->get();

        $pedidos = DB::select(DB::raw("select p.id, p.created_at, c.nome as cliente, s.descricao as status, string_agg(pr.nome, ', ') as produtos from tb_pedidos p inner join tb_clientes c on p.cliente_id = c.id
            inner join tb_status s on p.status_id = s.id
            inner join tb_pedidos_produtos pp on p.id = pp.pedido_id
            inner join tb_produtos pr on pp.produto_id = pr.id
            group by p.id, c.nome, s.descricao order by p.created_at asc"));

        $table['headers'] = ['Numero', 'Status', 'Cliente', 'Produtos', 'Data de Criação'];

        foreach ($pedidos as $key => $pedido) {
            $table['rows'][$key]['fields'] = [
                '#'.$pedido->id,
                $pedido->status,
                $pedido->cliente,
                $pedido->produtos,
                $pedido->created_at
            ];
        }

        return view('listModel', ['table' => $table, 'title' => 'Pedidos']);
    }
}
