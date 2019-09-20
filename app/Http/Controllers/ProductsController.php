<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

use App\Imports\ProductImport;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    /**
     * Função para exibir os produtos cadastrados em formato JSON.
     */
    public function index()
    {
        return Product::orderBy('id')->get();
    }

    /**
     * Função para importar os dados da tabela excel.
     */
    public function store(Request $request)
    {
        // Validando o recebimento do arquivo
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Realizando a importação do arquivo
        Excel::import(new ProductImport, request()->file('file'));

        //Retortando a resposta de sucesso para o usuário
        return response('Produtos cadastrados com sucesso', Response::HTTP_OK);

    }

    /**
     * Função para atualizar o produto no banco de dados.
     *
     */
    public function update(Request $request, $id)
    {
        // Validando o recebimento do ID
        $id->validate([
           'id' => 'require'
        ]);
        // Validando o recebimento de todos os campos
        $request->validate([
            'im' => 'required',
            'name' => 'required',
            'free_shipping' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);

        //Atualizando o prouto
        $product = Product::findOrFail($id);
        $product->im = $request->im;
        $product->name = $request->name;
        $product->free_shipping = $request->free_shipping;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        //Retornando notificação para o usuário
        return response('Produto editado com sucesso', Response::HTTP_OK);
    }

    /**
     * Função para deletar o registro do produto do banco de dados.
     */
    public function destroy($id)
    {
        // Validando o recebimento do ID
        $id->validate([
            'id' => 'require'
        ]);

        // Excluindo o produto no banco de dados
        Product::destroy($id);

        //Retornando notificação para o usuário
        return response('Produto excluido com sucesso', Response::HTTP_OK);
    }
}
