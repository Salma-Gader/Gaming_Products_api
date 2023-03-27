<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('id')->get();

        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $user = Auth::user();
        $product = Product::create($request->all() + ['user_id' => $user->id]);
        return response()->json([
            'status' => true,
            'message' => "product Created successfully!",
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        $product->find($product->id);
        if (!$product) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {   
        $user = Auth::user();
        $product->update($request->all());

        if (!$product) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        if(!$user->can('edit every product') && $user->id != $product->user_id){
            return response()->json(['message' => "Can't update a product that isn't yours!"]);
        }
        return response()->json([
            'status' => true,
            'message' => "product Updated successfully!",
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        if (!$product) {
            return response()->json([
                'message' => 'product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'product deleted successfully'
        ], 200);
    }

    public function searchByCategory($searching)
    {
        $product=Product::join('categories','categories.id','=','products.category_id')
                         ->where('category','LIKE', "$searching%")->get();
        if(count($product)==0) {
            return response()->json([
                'message'=>'No products found !',
            ]);
        }
        return response()->json([
            'message'=>'The products you are looking for :',
            'Products'=> $product,
        ]);
    }
    }