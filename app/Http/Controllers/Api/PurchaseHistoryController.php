<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PurchaseHistoryCollection;
use App\Models\Order;
use Illuminate\Http\Request;

class PurchaseHistoryController extends Controller
{
    public function index($id)
    {
        return new PurchaseHistoryCollection(Order::where('user_id', $id)->latest()->get());
    }
    
    public function updateTransaction(Request $request){
        $order_code = $request->code;
        $order = Order::where('code',$order_code)->count();
        if($order > 0){
            $data = Order::where('code',$order_code)->first();
            $data->payment_details = isset($request->details)?$request->details:null;
            $data->payment_status = 'paid';
            if($data->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully',
                    'order_code' => $order_code
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                    'order_code' => ''
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'order_code' => ''
            ]);
        }
    }
    
    public function cancel(Request $request){
        $order_code = $request->code;
        $order = Order::where('code',$order_code)->count();
        if($order > 0){
            $data = Order::where('code',$order_code)->first();

            foreach ($data->orderDetails as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
            if($data->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully',
                    'order_code' => $order_code
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                    'order_code' => ''
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'order_code' => ''
            ]);
        }
    }
}
