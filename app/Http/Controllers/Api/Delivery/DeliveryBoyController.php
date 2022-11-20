<?php

namespace App\Http\Controllers\API\Delivery;

use App\Http\Controllers\Controller;
use App\DeliveryBoy;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryBoyController extends Controller
{
    public function getDeliveryBoyOrders(Request $request)
    {
        if(Auth::guard('delivery-api')->check()){
            $user_id = Auth::user()->id;
            $delivery_boy_orders = Order::where('delivery_boy_id', $user_id)->with('detail')->get();
            return response()->json(['data' => $delivery_boy_orders, 'message' => 'Data get successfully'], 200);
        } else {
            return response()->json(['error'=> 'Unauthorized. Please login'], 401);
        }
    }

    public function changeDeliveryBoyAvailablityStatus(Request $request)
    {
        if(Auth::guard('delivery-api')->check()){
            $user_id = Auth::user()->id;
            $delivery_boy = DeliveryBoy::where('id', $user_id)->first();
            $delivery_boy->update([
                'availability_status' => $request->availability_status,
            ]);

            return response()->json(['data' => $delivery_boy, 'message' => 'Availability status changed successfully'], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function changeDeliveryBoyOrderStatus(Request $request)
    {
        if(Auth::guard('delivery-api')->check()){
            $user_id = Auth::user()->id;
            $order = Order::where('id', $request->order_id)->first();
            $order->update(['order_status' => $request->order_status]);
            return response()->json(['delivery_boy' => Auth::user(), 'order' => $order, 'message' => 'Order status changed successfully'], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
