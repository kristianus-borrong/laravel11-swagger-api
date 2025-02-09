<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     *    @OA\Get(
     *       path="/api/customers",
     *       tags={"Customers"},
     *       operationId="customers",
     *       summary="Customers",
     *       description="Mengambil Data Customers",
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\MediaType(mediaType="application/json"),
     *      ),
     *  )
     */
    public function index()
    {
        $query = Customer::all();
        if ($query->toArray() != null) {
            return response()->json([
                'status' => "success",
                'message' => 'Mendapatkan semua pelanggan',
                'data' => $query->toArray()
            ], 200);
        }
        return response()->json([
            'status' => "error",
            'message' => 'Tidak ada pelanggan',
            'data' => null
        ], 400);
    }

    /**
     *    @OA\Post(
     *       path="/api/customers",
     *       tags={"Customers"},
     *       operationId="storeCustomer",
     *       summary="Store Customer",
     *       description="Menambahkan pelanggan baru",
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string", example="John Doe"),
     *               @OA\Property(property="email", type="string", example="john@example.com"),
     *               @OA\Property(property="phone", type="string", example="1234567890"),
     *               @OA\Property(property="address", type="string", example="123 Main Street"),
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
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|numeric|digits:10',
            'address' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        // Insert data
        DB::table('customers')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        return response()->json([
            'status' => "success",
            'message' => 'Membuat pelanggan baru berhasil',
            'data' => null
        ], 200);
    }


    /**
     *    @OA\Get(
     *       path="/api/customers/{id}",
     *       tags={"Customers"},
     *       operationId="showCustomer",
     *       summary="Show Customer",
     *       description="Mendapatkan detail pelanggan berdasarkan ID",
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
        $query = DB::table('customers')->where('id_customer', $id)->get();
        if ($query->toArray() == null) {
            return response()->json([
                'status' => "error",
                'message' => 'Pelanggan tidak ditemukan',
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
     *       path="/api/customers/{id}",
     *       tags={"Customers"},
     *       operationId="updateCustomer",
     *       summary="Update Customer",
     *       description="Mengupdate data pelanggan",
     *       @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           @OA\Schema(
     *              type="integer"
     *           )
     *       ),
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string", example="John Doe"),
     *               @OA\Property(property="email", type="string", example="john@example.com"),
     *               @OA\Property(property="phone", type="string", example="1234567890"),
     *               @OA\Property(property="address", type="string", example="123 Main Street"),
     *           )
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
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|numeric|digits:10',
            'address' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        // Insert data
        DB::table('customers')->where('id_customer', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        $query = DB::table('customers')->where('id_customer', $id)->get();
        return response()->json([
            'status' => "success",
            'message' => 'Mengupdate pelanggan berhasil',
            'data' => $query->toArray()
        ], 200);    
        
    }

    /**
     *    @OA\Delete(
     *       path="/api/customers/{id}",
     *       tags={"Customers"},
     *       operationId="deleteCustomer",
     *       summary="Delete Customer",
     *       description="Menghapus data pelanggan",
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
        $query = DB::table('customers')->where('id_customer', $id)->delete();
        if ($query) {
            return response()->json([
                'status' => "success",
                'message' => 'Menghapus pelanggan berhasil',
                'data' => null
            ], 200);
        }
        return response()->json([
            'status' => "error",
            'message' => 'Gagal menghapus order',
            'data' => null
        ], 400);
    }
}
