<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(){
        return Supplier::paginate(5);
    }

    public function show($id){
        return Supplier::findOrFail($id);
    }

    public function store(Request $request){

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => [
                'required',
                'regex:/^\d{2}-\d{4,5}-\d{4}$/'
            ],
        ];
    
        $messages = [
            'name.required' => 'O nome do fornecedor é obrigatório.',
            'name.string' => 'O nome do fornecedor deve ser um texto.',
            'name.max' => 'O nome do fornecedor não pode ter mais de 255 caracteres.',
            'email.required' => 'O email do fornecedor é obrigatório.',
            'email.email' => 'Forneça um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'phone.required' => 'O telefone do fornecedor é obrigatório.',
            'phone.regex' => 'O formato do telefone está inválido. Use o formato XX-XXXX-XXXX ou XX-XXXXX-XXXX.',
        ];

        $validated = $request->validate($rules, $messages);

        $supplier = Supplier::create($validated);

        return response()->json([
            'status' => true,
            'supplier' => $supplier,
            'messages' => 'Fornecedor cadastrado com sucesso!'
        ]);
    }

    public function update(Request $request, $id){

        $supplier = Supplier::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => [
                'required',
                'regex:/^\d{2}-\d{4,5}-\d{4}$/'
            ],
        ];
    
        $messages = [
            'name.required' => 'O nome do fornecedor é obrigatório.',
            'name.string' => 'O nome do fornecedor deve ser um texto.',
            'name.max' => 'O nome do fornecedor não pode ter mais de 255 caracteres.',
            'email.required' => 'O email do fornecedor é obrigatório.',
            'email.email' => 'Forneça um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'phone.required' => 'O telefone do fornecedor é obrigatório.',
            'phone.regex' => 'O formato do telefone está inválido. Use o formato XX-XXXX-XXXX ou XX-XXXXX-XXXX.',
        ];

        $validated = $request->validate($rules, $messages);

        $supplier->update($validated);

        return response()->json([
            'status' => true,
            'supplier' => $supplier,
            'messages' => "Fornecedor atualizado com sucesso!"
        ]);
    }

    public function delete($id){

        $supplier = Supplier::find($id);

        if(!$supplier){
            return response()->json([
                'status' => false,
                'messages' => 'Fornecedor não encontrado!'
            ]);
        }

        $supplier->delete();

        return response()->json([
            'status' => true,
            'supplier' => $supplier,
            'messages' => 'Fornecedor excluído com sucesso!'
        ]);
    }
}
