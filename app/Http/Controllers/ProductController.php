<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){

        return Product::with(['category', 'supplier'])->paginate(5);
    }

    public function show($id){

        return Product::with(['category', 'supplier'])->findOrFail($id);
    }

    public function store(Request $request){

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ];

        $messages = [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.string' => 'O nome do produto deve ser um texto.',
            'name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
            'description.required' => 'A descrição do produto é obrigatório.',
            'description.string' => 'A descrição do produto deve ser um texto.',
            'description.max' => 'A descrição do produto não pode ter mais de 255 caracteres.',
            'category_id.required' => 'A categoria do produto é obrigatória.',
            'category_id.exists' => 'A categoria selecionada é inválida.',
            'supplier_id.required' => 'O fornecedor do produto é obrigatório.',
            'supplier_id.exists' => 'O fornecedor selecionado é inválido.',
            'stock.required' => 'O estoque é obrigatório.',
            'stock.integer' => 'O estoque deve ser um número inteiro.',
            'stock.min' => 'O estoque deve ser pelo menos 1 unidade.',
            'price.required' => 'O preço do produto é obrigatório.',
            'price.numeric' => 'O preço do produto deve ser um número.',
            'price.min' => 'O preço do produto deve ser no mínimo 0.',
        ];

        $validated = $request->validate($rules, $messages);

        $product = Product::create($validated);

        return response()->json([
            'status'=>true,
            'message'=> 'Produto cadastrado com sucesso!',
            'product'=> $product
        ], 201);
    }

    public function update(Request $request, $id){

        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'stock' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
        ];

        $messages = [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.string' => 'O nome do produto deve ser um texto.',
            'name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
            'description.required' => 'A descrição do produto é obrigatório.',
            'description.string' => 'A descrição do produto deve ser um texto.',
            'description.max' => 'A descrição do produto não pode ter mais de 255 caracteres.',
            'category_id.required' => 'A categoria do produto é obrigatória.',
            'category_id.exists' => 'A categoria selecionada é inválida.',
            'supplier_id.required' => 'O fornecedor do produto é obrigatório.',
            'supplier_id.exists' => 'O fornecedor selecionado é inválido.',
            'stock.required' => 'O estoque é obrigatório.',
            'stock.integer' => 'O estoque deve ser um número inteiro.',
            'stock.min' => 'O estoque deve ser pelo menos 1 unidade.',
            'price.required' => 'O preço do produto é obrigatório.',
            'price.numeric' => 'O preço do produto deve ser um número.',
            'price.min' => 'O preço do produto deve ser no mínimo 0.',
        ];

        $validated = $request->validate($rules, $messages);

        $product = Product::findOrFail($id);

        $product->update($validated);

        return response()->json([
            'status'=> true,
            'messages'=> 'Produto atualizado com sucesso!',
            '$product'=> $product
        ]);
    }

    public function delete($id){

        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'status'=> true,
                'messages'=> 'Produto não econtrado!'
            ]);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'messages' => 'Produto excluído com sucesso!',
            '$product' => $product
        ]);
    }

}
