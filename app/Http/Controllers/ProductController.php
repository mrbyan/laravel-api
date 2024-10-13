<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::select('id', 'name', 'price')->get();
        return ApiFormatter::createApi(200, 'Data produk ditemukan', $products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
            'price' => 'required|numeric',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Produk gagal ditambahkan', $validator->errors());
        }

        $product = Product::create($request->all());

        return ApiFormatter::createApi(201, 'Produk berhasil ditambahkan', $product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiFormatter::createApi(404, 'Produk tidak ditemukan');
        }
        return ApiFormatter::createApi(200, 'Produk ditemukan', $product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiFormatter::createApi(404, 'Produk tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:64',
            'price' => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Produk gagal diperbarui', $validator->errors());
        }

        $product->update($request->all());

        return ApiFormatter::createApi(200, 'Produk berhasil diperbarui', $product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiFormatter::createApi(404, 'Produk tidak ditemukan');
        }

        $product->delete();

        return ApiFormatter::createApi(200, 'Produk berhasil dihapus');
    }

    public function search(Request $request)
    {
        // Validasi input pencarian
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:64',
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Pencarian gagal', $validator->errors());
        }

        $products = Product::where('name', 'like', '%' . $request->query . '%')->get();

        if ($products->isEmpty()) {
            return ApiFormatter::createApi(404, 'Produk tidak ditemukan');
        }

        return ApiFormatter::createApi(200, 'Produk ditemukan', $products);
    }
}
