<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FormController extends Controller
{
    public function index()
    {
        return view('form.index');
    }

    public function add(Request $request)
    {
        return view('form.form');
    }

    public function edit(Request $request)
    {
        $product = Product::find($request->id);
        return view('form.form', compact('product'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'stock' => 'required',
            'available' => 'required',
        ]);

        if($request->has('id')){
            $product = Product::find($request->id);
            $product->name = $request->name;
            $product->code = $request->code;
            $product->stock = $request->stock;
            $product->available = $request->available;
            if($request->has('image')){
                Storage::delete($product->image_path);
                $product->image_path = $request->file('image')->store('public/images/products');
            }
            $product->updated_by = auth()->user()->id ?? null;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => $product
            ]);
        }else{
            $product = new Product;
            $product->name = $request->name;
            $product->code = $request->code;
            $product->stock = $request->stock;
            $product->available = $request->available;
            if($request->has('image')) {
                $product->image_path = $request->file('image')->store('public/images/products');
            }
            $product->created_by = auth()->user()->id ?? null;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Product saved successfully',
                'data' => $product
            ]);
        }
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        Storage::delete($product->image_path);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ]);
    }

    public function list(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')->where('deleted_at', null);

        return DataTables::eloquent($products)
            ->addColumn('action', function($product){
                return '<a href="'.route('product.edit', $product->id).'" class="btn btn-sm btn-primary">Edit</a> <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="deleteProduct('.$product->id.')">Delete</a>';
            })
            ->editColumn('image_path', function($product){
                return '<img src="'.Storage::url($product->image_path).'" width="100" height="100">';
            })
            ->editColumn('available', function($product){
                return (bool)$product->available;
            })
            ->rawColumns(['action', 'image_path'])
            ->make(true);
    }
}
