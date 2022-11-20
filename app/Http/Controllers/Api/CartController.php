<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\Color;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index($id)
    {
        return new CartCollection(Cart::where('user_id', $id)->latest()->get());
    }

    public function add(Request $request)
    {
        try{
        $product = Product::findOrFail($request->id);
 
        $variant= '';
        $str = '';
        if($request->color && $request->variant){
             $str = Color::where('code', $request['color'])->first()->name;
            
          $variant = $str.'-'.$variant;
        }elseif($request->color && !$request->variant){
             $str = Color::where('code', $request['color'])->first()->name;
            $variant = $str;
        }elseif(!$request->color && $request->variant){
            $variant = $request->variant;
        }
    
        
       
        $tax = 0;

        if ($variant == null && $str == null){
            $price = $product->unit_price;
            $product_stock = $product->current_stock;
        }else{ 
            //$variations = json_decode($product->variations);
           
            $product_stock = $product->stocks->where('variant', $variant)->first();
          
            $price = $product_stock->price;
        }
        if($product_stock < $request->quantity){
            return response()->json(['message' =>'Stock Available'.$product->current_stock], 400);
       }elseif($request->quantity < 0){
         return response()->json(['message' =>'Error, Minimum 1 quantity required'], 400);   
       }
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        if($product->tax_type == 'percent'){
            $tax = ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $tax = $product->tax;
        }


        Cart::updateOrCreate([
            'user_id' => $request->user_id,
            'product_id' => $request->id,
            'variation' => $variant
        ], [
            'price' => $price,
            'tax' => $tax,
            'shipping_cost' => $product->shipping_type == 'free' ? 0 : $product->shipping_cost,
            'quantity' => DB::raw('quantity + '.$request->quantity)
        ]);

        return response()->json([
            'message' => 'Product added to cart successfully'
        ]);
    }catch(\Exception $e){
    return $e->getMessage();
        }
    }

    public function changeQuantity(Request $request)
    {
        $product = Product::where('id',$request->product_id)->first();
        if($product->current_stock < $request->quantity){
             return response()->json(['message' =>'Stock Available'.$product->current_stock], 400);
        }elseif($request->quantity < 0){
          return response()->json(['message' =>'Error, Minimum 1 quantity required'], 400);   
        }
        
    
        $cart = Cart::findOrFail($request->id);
        $cart->update([
            'quantity' => $request->quantity
        ]);
        return response()->json(['message' => 'Cart updated'], 200);
    }

    public function destroy($id)
    {
      
      $delete = Cart::destroy($id);
     
        return response()->json(['message' => 'Product is successfully removed from your cart'], 200);
    }
}
