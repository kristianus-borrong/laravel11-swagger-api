<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     *    @OA\Get(
     *       path="/api/products",
     *       tags={"Products"},
     *       operationId="products",
     *       summary="Products",
     *       description="Mengambil Data Products",
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\MediaType(mediaType="application/json"),
     *      ),
     *  )
     */
    public function index()
    {
        $query = Products::all();
        if ($query->toArray() != null) {
            return response()->json([
                'status' => "success",
                'message' => 'Mendapatkan semua Products',
                'data' => $query->toArray()
            ], 200);
        }
        return response()->json([
            'status' => "error",
            'message' => 'Tidak ada Products',
            'data' => null
        ], 400);
    }

    /**
     *    @OA\Post(
     *       path="/api/products",
     *       tags={"Products"},
     *       operationId="storeProduct",
     *       summary="Store Product",
     *       description="Menambahkan Products baru",
     *       @OA\RequestBody(
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string", example="John Doe"),
     *               @OA\Property(property="description", type="string", example="John Doe"),
     *               @OA\Property(property="price", type="bigint", example="100000"),
     *               @OA\Property(property="stock", type="integer", example="0"),
     *           ),
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\MediaType(mediaType="application/json"),
     *      ),
     *  )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'description' => 'nullable',
            'price' => 'required|integer',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        // Insert data
        DB::table('products')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
        return response()->json([
            'status' => "success",
            'message' => 'Membuat pelanggan baru berhasil',
            'data' => null
        ], 200);
    }

    /**
     *    @OA\Get(
     *       path="/api/products/{id}",
     *       tags={"Products"},
     *       operationId="showProduct",
     *       summary="Show Product",
     *       description="Mendapatkan detail produk berdasarkan ID",
     *       @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           @OA\Schema(
     *              type="integer"
     *           )
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\MediaType(mediaType="application/json"),
     *      ),
     *  )
     */
    public function show(string $id)
    {
        $query = DB::table('products')->where('id_product', $id)->get();
        if ($query->toArray() == null) {
            return response()->json([
                'status' => "error",
                'message' => 'Produk tidak ditemukan',
                'data' => null
            ], 404);
        }
        return response()->json([
            'status' => "success",
            'message' => 'Mendapatkan pelanggan berhasil',
            'data' => $query->toArray()
        ], 200);
    }

    /**
     *    @OA\Put(
     *       path="/api/products/{id}",
     *       tags={"Products"},
     *       operationId="updateProduct",
     *       summary="Update Product",
     *       description="Mengupdate data produk",
     *       @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           @OA\Schema(
     *              type="integer"
     *           )
     *       ), 
     *       @OA\RequestBody(
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string", example="John Doe"),
     *               @OA\Property(property="description", type="string", example="John Doe"),
     *               @OA\Property(property="price", type="bigint", example="100000"),
     *               @OA\Property(property="stock", type="integer", example="0"),
     *           ),
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\MediaType(mediaType="application/json"),
     *      ),
     *  )
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'description' => 'nullable',
            'price' => 'required|integer',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        // Insert data
        DB::table('products')->where('id_product', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ]);
        $query = DB::table('products')->where('id_product', $id)->get();
        return response()->json([
            'status' => "success",
            'message' => 'Mengupdate produk berhasil',
            'data' => $query->toArray()
        ], 200);  
    }

    /**
     *    @OA\Delete(
     *       path="/api/products/{id}",
     *       tags={"Products"},
     *       operationId="deleteProduct",
     *       summary="Delete Product",
     *       description="Menghapus data produk",
     *       @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           @OA\Schema(
     *              type="integer"
     *           )
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\MediaType(mediaType="application/json"),
     *      ),
     *  )
     */
    public function destroy(string $id)
    {
        $query = DB::table('products')->where('id_product', $id)->delete();
        if ($query) {
            return response()->json([
                'status' => "success",
                'message' => 'Menghapus produk berhasil',
                'data' => null
            ], 200);
        }
        return response()->json([
            'status' => "error",
            'message' => 'Gagal menghapus pelanggan',
            'data' => null
        ], 400);
    }
}
