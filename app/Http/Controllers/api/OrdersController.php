<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     *    @OA\Get(
     *       path="/api/orders",
     *       tags={"Orders"},
     *       operationId="orders",
     *       summary="Orders",
     *       description="Mendapatkan semua orders",
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\MediaType(mediaType="application/json"),
     *      ),
     *  )
     */
    public function index()
    {
        $query = Order::all();
        if ($query->toArray() != null) {
            return response()->json([
                'status' => "success",
                'message' => 'Mendapatkan semua orders',
                'data' => $query->toArray()
            ], 200);
        }
        return response()->json([
            'status' => "error",
            'message' => 'Tidak ada orders',
            'data' => null
        ], 400);
    }

    /**
     *    @OA\Post(
     *       path="/api/orders",
     *       tags={"Orders"},
     *       operationId="storeOrder",
     *       summary="Store Order",
     *       description="Menambahkan pesanan baru",
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               @OA\Property(property="id_customer", type="integer", example="1"),
     *               @OA\Property(property="date", type="string", format="date", example="2024-01-01"),
     *               @OA\Property(property="status", type="string", enum={"pending", "completed", "cancelled"}, example="pending"),
     *               @OA\Property(property="order_items", type="array", @OA\Items(
     *                   @OA\Property(property="id_product", type="integer", example="1"),
     *                   @OA\Property(property="quantity", type="integer", example="1"),
     *                   @OA\Property(property="price", type="number", example="1000"),
     *               )),
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
            'id_customer' => 'required|exists:customers,id_customer',
            'date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'order_items' => 'required|array',
            'order_items.*.id_product' => 'required|exists:products,id_product',
            'order_items.*.quantity' => 'required|numeric|min:1',
            'order_items.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }

        $order = Order::create([
            'id_customer' => $request->id_customer,
            'date' => $request->date,
            'status' => $request->status
        ]);
        foreach ($request->order_items as $item) {
            OrderItems::create([
                'id_order' => $order->id,
                'id_product' => $item['id_product'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        return response()->json([
            'status' => "success",
            'message' => 'Membuat order baru berhasil',
            'data' => null
        ], 200);
    }

    /**
     *    @OA\Get(
     *       path="/api/orders/{id}",
     *       tags={"Orders"},
     *       operationId="showOrder",
     *       summary="Show Order",
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
        $query = DB::table('orders')->where('id_order', $id)->get();
        if ($query->toArray() == null) {
            return response()->json([
                'status' => "error",
                'message' => 'Order tidak ditemukan',
                'data' => null
            ], 404);
        }
        return response()->json([
            'status' => "success",
            'message' => 'Mendapatkan order berhasil',
            'data' => $query->toArray()
        ], 200);
    }

    /**
     *    @OA\Delete(
     *       path="/api/orders/{id}",
     *       tags={"Orders"},
     *       operationId="deleteOrder",
     *       summary="Delete Order",
     *       description="Menghapus data order",
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
        $query = DB::table('orders')->where('id_order', $id)->delete();
        if ($query) {
            return response()->json([
                'status' => "success",
                'message' => 'Menghapus order berhasil',
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
