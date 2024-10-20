<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ReportProductController extends Controller
{
    public function index(Request $request){

        $query = Product::with(['category', 'supplier'])
        ->when($request->input('name'), function($q, $name){
            $q->where('name', 'like', '%' . $name . '%');
        })
        ->when($request->input('unit_of_measure'), function($q, $unit_of_measure){
            $q->where('unit_of_measure', $unit_of_measure);
        })
        ->when($request->input('category_id'), function($q, $category_id){
            $q->where('category_id', $category_id);
        })
        ->when($request->input('supplier_id'), function($q, $supplier_id){
            $q->where('supplier_id', $supplier_id);
        });

        return $query->paginate(5);
    }

    public function show($id){

        return Product::with(['category', 'supplier'])->findOrFail($id);
    }

    public function totalProducts(Request $request){

        $totalStock = Product::when($request->input('id'), function($q, $id){
            $q->where('id', $id);
        })
        ->when($request->input('category_id'), function($q, $category_id){
            $q->where('category_id', $category_id);
        })
        ->when($request->input('supplier_id'), function($q, $supplier_id){
            $q->where('supplier_id', $supplier_id);
        })
        ->when($request->input('unit_of_measure'), function($q, $unit_of_measure){
            $q->where('unit_of_measure', $unit_of_measure);
        })
        ->sum('stock');

        return response()->json([
            'status'=>true,
            'product'=>$totalStock
        ]);
    }
}
