<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::select('id', 'name')->get();
        return ApiFormatter::createApi(200, 'Data kategori ditemukan', $categories);
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
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Kategori gagal ditambahkan', $validator->errors());
        }

        $product = Category::create([
            'name' => $request->name,
        ]);

        return ApiFormatter::createApi(201, 'Kategori berhasil ditambahkan', $product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ApiFormatter::createApi(404, 'Kategori tidak ditemukan');
        }
        return ApiFormatter::createApi(200, 'Kategori ditemukan', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ApiFormatter::createApi(404, 'Kategori tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:64',
        ]);

        if ($validator->fails()) {
            return ApiFormatter::createApi(400, 'Kategori gagal diperbarui', $validator->errors());
        }

        $category->update($request->all());

        return ApiFormatter::createApi(200, 'Kategori berhasil diperbarui', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ApiFormatter::createApi(404, 'Kategori tidak ditemukan');
        }

        $category->delete();

        return ApiFormatter::createApi(200, 'Kategori berhasil dihapus');
    }
}
