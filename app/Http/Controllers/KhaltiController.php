<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KhaltiController extends Controller
{
    public function walletKhalti(){
        if(Session::has('payment_type')){
            if(Session::get('payment_type') == 'wallet_payment'){
              return view('frontend.payment.khalti');
            }
        }
    }
    public function khalti()
    {
        if(Session::has('payment_type')){
            if(Session::get('payment_type') == 'cart_payment'){
                // dd('hi');
                return view('frontend.payment.khalti');
            }
            // elseif (Session::get('payment_type') == 'customer_package_payment') {
            //     $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
            //     return view('frontend.payment.stripe', compact('customer_package'));
            // }
            // elseif (Session::get('payment_type') == 'seller_package_payment') {
            //     $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
            //     return view('frontend.payment.stripe', compact('seller_package'));
            // }
        }
    }
}
