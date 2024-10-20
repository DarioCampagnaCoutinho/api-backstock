<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function addStock(Request $request, $id){

        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $messages = [
            'quantity.required' => 'A quantidade é obrigatória para adicionar estoque.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade deve ser de pelo menos 1 unidade para adicionar estoque.',
        ];
        $validated = $request->validate($rules, $messages);

        DB::transaction(function () use ($id, $validated){

            $product = Product::lockForUpdate()->findOrFail($id);

            $product->increment('stock', $validated['quantity']);

            $product->stockMovement()->create([
                'quantity'=> $validated['quantity'],
                'type'=>'in'
            ]);
        
        });

        return response()->json([
            'status'=> true,
            'messages' => 'Estoque atualizado com sucesso!',
            'product' => Product::findOrFail($id)
        ]);
    }

    public function removeStock(Request $request, $id){

        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $messages = [
            'quantity.required' => 'A quantidade é obrigatória para adicionar estoque.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade deve ser de pelo menos 1 unidade para adicionar estoque.',
        ];

        $validated = $request->validate($rules, $messages);

        DB::transaction(function () use ($id, $validated){

            $product = Product::lockForUpdate()->findOrFail($id);

            $product->decrement('stock', $validated['quantity']);

            $product->stockMovement()->create([
                'quantity'=> $validated['quantity'],
                'type'=> 'out'
            ]);

        });

        return response()->json([
            'status'=> true,
            'messages'=> 'Estoque atualizado com sucesso!',
            'product' => Product::findOrFail($id)
        ]);
    }
}
