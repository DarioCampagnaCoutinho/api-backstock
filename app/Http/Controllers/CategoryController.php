<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

        return Category::paginate(5);
    }

    public function show($id){

        return Category::findOrFail($id);
    }

    public function store(Request $request){

        $rules = [
            'name' => 'required|string|max:255|unique:categories,name'
        ];

        $messages = [
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.string' => 'O nome da categoria deve ser um texto.',
            'name.max' => 'O nome da categoria não pode ter mais de 255 caracteres.',
            'name.unique' => 'Esta categoria já está cadastrada.',
        ];

        $validated = $request->validate($rules, $messages);

        $category = Category::create($validated);

        return response()->json([
            'status' => true,
            'category' => $category,
            'message' => 'Categoria cadastrada com sucesso!'
        ]);
    }

    public function update(Request $request, $id){

        $category = Category::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255|unique:categories,name'
        ];

        $messages = [
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.string' => 'O nome da categoria deve ser um texto.',
            'name.max' => 'O nome da categoria não pode ter mais de 255 caracteres.',
            'name.unique' => 'Esta categoria já está cadastrada.',
        ];

        $validated = $request->validate($rules, $messages);

        $category->update($validated);

        return response()->json([
            'status' => true,
            'category' => $category,
            'message' => 'Categoria atualizada com sucesso!'
        ]);
    }

    public function delete($id){

        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'status' => false,
                'category' => 'Categoria não existe!'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'category' => $category,
            'message' => 'Categoria excluída com sucesso!'
        ]);
    }
}
