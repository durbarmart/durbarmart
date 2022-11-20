<?php

namespace App\Http\Controllers;

use App\BusinessSetting;
use App\CouponUsage;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\OTPVerificationController;
use App\Mail\CustomerEmail;
use App\Mail\InvoiceEmailManager;
use App\Models\OrderDetail as ModelsOrderDetail;
use App\Order;
use App\OrderDetail;
use App\OtpConfiguration;
use App\Product;
use App\User;
use Auth;
// use CoreComponentRepository;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Mail;
use PDF;
use Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct();
            // dd($orders);

        if ($request->payment_status != null) {
            $orders = $orders->where('order_details.payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = \App\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('frontend.seller.orders', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    public function delivery_orders(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $delivery_boy=\App\DeliveryBoy::where('user_id',Auth::user()->id)->first();
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->where('orders.delivery_id', $delivery_boy->id)
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select('orders.id')
            ->distinct();
        // $orders=DB::table('orders')->orderBy('code', 'desc')->where('delivery_id',$delivery_boy->id)->get();
        // dd($orders);

        if ($request->payment_status != null) {
            $orders = $orders->where('order_details.payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = \App\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('frontend.delivery.orders', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    /**
     * Display a listing of the resource to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_orders(Request $request)
    {
        // CoreComponentRepository::instantiateShopRepository();

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $admin_user_id = User::where('user_type', 'admin')->first()->id;
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', $admin_user_id)
            ->select('orders.id')
            ->distinct();

        if ($request->payment_type != null) {
            $orders = $orders->where('order_details.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        $orders = $orders->paginate(15);
        // dd($orders);
        return view('orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search', 'admin_user_id'));
    }

    public function seller_orders(Request $request)
    {
        // dd('hi');
        // CoreComponentRepository::instantiateShopRepository();

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $vendor_id=null;
        $admin_user_id=array();
        foreach(User::where('user_type', 'seller')->get() as $user){
            array_push($admin_user_id,$user->id);

        }

        
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereIn('order_details.seller_id', (array)$admin_user_id)
            ->select('orders.id')
            ->distinct();

        if ($request->payment_type != null) {
            $orders = $orders->where('order_details.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->seller != null) {
            
            $orders = $orders
                ->where('order_details.seller_id', $request->seller);
            $vendor_id=$request->seller;
            
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        $orders = $orders->paginate(15);
        
        return view('orders.seller-orders', compact('orders', 'delivery_status', 'sort_search', 'admin_user_id','vendor_id'));
    }

    /**
     * Display a listing of the sales to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        // CoreComponentRepository::instantiateShopRepository();

        $sort_search = null;
        $orders = Order::orderBy('code', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        $orders = $orders->paginate(15);
        return view('sales.index', compact('orders', 'sort_search'));
    }

    public function order_index(Request $request)
    {
        if (Auth::user()->user_type == 'staff' && Auth::user()->staff->pick_up_point != null) {
            //$orders = Order::where('pickup_point_id', Auth::user()->staff->pick_up_point->id)->get();
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.pickup_point_id', Auth::user()->staff->pick_up_point->id)
                ->select('orders.id')
                ->distinct()
                ->paginate(15);

            return view('pickup_point.orders.index', compact('orders'));
        } else {
            //$orders = Order::where('shipping_type', 'Pick-up Point')->get();
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.shipping_type', 'pickup_point')
                ->select('orders.id')
                ->distinct()
                ->paginate(15);

            return view('pickup_point.orders.index', compact('orders'));
        }
    }

    public function pickup_point_order_sales_show($id)
    {
        if (Auth::user()->user_type == 'staff') {
            $order = Order::findOrFail(decrypt($id));
            return view('pickup_point.orders.show', compact('order'));
        } else {
            $order = Order::findOrFail(decrypt($id));
            return view('pickup_point.orders.show', compact('order'));
        }
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('sales.show', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order;
        if (Auth::check()) {
            $order->user_id = Auth::user()->id;
        } else {
            $order->guest_id = mt_rand(100000, 999999);
        }

        $order->shipping_address = json_encode($request->session()->get('shipping_info'));

        $order->payment_type = $request->payment_option;
        $order->delivery_viewed = '0';
        $order->payment_status_viewed = '0';
        $order->code = date('Ymd-His') . rand(10, 99);
        $order->date = strtotime('now');

        if ($order->save()) {
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $admin_products = array();
            $seller_products = array();

            //Calculate Shipping Cost
            if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {

                $shipping = \App\BusinessSetting::where('type', 'flat_rate_shipping_cost')->first()->value;
            } elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {

                foreach (Session::get('cart') as $key => $cartItem) {
                    $product = \App\Product::find($cartItem['id']);
                    if ($product->added_by == 'admin') {
                        array_push($admin_products, $cartItem['id']);
                    } else {
                        $product_ids = array();
                        if (array_key_exists($product->user_id, $seller_products)) {
                            $product_ids = $seller_products[$product->user_id];
                        }
                        array_push($product_ids, $cartItem['id']);
                        $seller_products[$product->user_id] = $product_ids;
                    }
                }
                if (!empty($admin_products)) {
                    $shipping = \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value;
                }
                if (!empty($seller_products)) {
                    foreach ($seller_products as $key => $seller_product) {
                        $shipping += \App\Shop::where('user_id', $key)->first()->shipping_cost;
                    }
                }
            }
            //End Shipping Cost Calculation
            //Order Details Storing
            foreach (Session::get('cart') as $key => $cartItem) {
                $product = Product::find($cartItem['id']);
                $userType = User::where('id', $product->user_id)->first()->user_type;

                $product_ids = array();
                if (array_key_exists($product->user_id, $seller_products)) {
                    $product_ids = $seller_products[$product->user_id];
                }
                array_push($product_ids, $cartItem['id']);
                $seller_products[$product->user_id] = $product_ids;
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];

                $product_variation = $cartItem['variant'];

                if ($product_variation != null) {

                    $product_stock = $product->stocks->where('variant', $product_variation)->first();
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();

                } else {
                    $product->current_stock -= $cartItem['quantity'];
                    $product->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];

                //Dividing Shipping Costs

                if ($cartItem['shipping_type'] == 'home_delivery') {

                    if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
                        $order_detail->shipping_cost = $shipping / count(Session::get('cart'));
                    } elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {

                        if ($product->added_by == 'admin') {
                            $order_detail->shipping_cost = \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value / count($admin_products);
                        } else {
                            $order_detail->shipping_cost = \App\Shop::where('user_id', $product->user_id)->first()->shipping_cost / count($seller_products[$product->user_id]);
                        }
                    } else {
                        $order_detail->shipping_cost = \App\Product::find($cartItem['id'])->shipping_cost;
                        $shipping += \App\Product::find($cartItem['id'])->shipping_cost;
                    }
                } else {
                    $order_detail->shipping_cost = 0;
                    $order_detail->pickup_point_id = $cartItem['pickup_point'];
                }
                //End of storing shipping cost

                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale++;
                $product->save();
            }

            $order->grand_total = $subtotal + $tax + $shipping;

            if (Session::has('coupon_discount')) {
                $order->grand_total -= Session::get('coupon_discount');
                $order->coupon_discount = Session::get('coupon_discount');

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Session::get('coupon_id');
                $coupon_usage->save();
            }

            $order->save();

            set_time_limit(1500);
            //stores the pdf for invoice
            $pdf = PDF::setOptions([
                'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/'),
            ])->loadView('invoices.customer_invoice', compact('order'));
            $output = $pdf->output();
            file_put_contents(public_path('invoices/' . 'Order#' . $order->code . '.pdf'), $output);
            $data['view'] = 'emails.invoice';
            $data['subject'] = 'Order Placed - ' . $order->code;
            $data['from'] = Config::get('mail.username');
            $data['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
            $data['file'] = public_path('invoices/' . 'Order#' . $order->code . '.pdf');
            $data['file_name'] = 'Order#' . $order->code . '.pdf';
            if (Config::get('mail.username') != null) {
            // //     //     // if(env('MAIL_USERNAME') != null){
                    try {
                        Mail::to($request->session()->get('shipping_info')['email'])->send(new InvoiceEmailManager($data));
                        Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($data));
            // //     //         Mail::to(User::where('user_type', 'admin')->first()->email)->send(new InvoiceEmailManager($data));
            // //     //         // dd($request->session()->get('shipping_info')['email']);
            // //     //         // dispatch(new SendInvoiceEmail($array));
            // //     //         // dispatch(new SendInvoiceEmail(User::where('user_type', 'admin')->first()->email, $array));
                        Log::info('Mail Sent');
            // //     //         // Mail::to($request->session()->get('shipping_info')['email'])->queue(new InvoiceEmailManager($array));
            // //     //         // Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                    } catch (\Exception $e) {
                        Log::info($e->getMessage());
                    }
                }
                unlink($data['file']);

            // dd($seller_products);
            // foreach ($seller_products as $key => $seller_product) {
            //     $user = User::where('id', $key)->first();
            //     // dd(\App\User::find($key)->email);
            //     try {
            //         $pdf = PDF::setOptions([
            //             'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
            //             'logOutputFile' => storage_path('logs/log.htm'),
            //             'tempDir' => storage_path('logs/'),
            //         ])->loadView('invoices.sellers_invoice', compact('order', 'user'));
            //         $output = $pdf->output();
            //         file_put_contents(public_path('invoices/seller/' . 'Order#' . $order->code . '.pdf'), $output);
            //         $array['view'] = 'emails.invoice';
            //         $array['subject'] = 'Order Placed - ' . $order->code;
            //         $array['from'] = Config::get('mail.username');
            //         $array['content'] = 'Hello. A new order has been placed. Please check the attached invoice.';
            //         $array['file'] = public_path('invoices/seller/' . 'Order#' . $order->code . '.pdf');
            //         $array['file_name'] = 'Order#' . $order->code . '.pdf';

            //         Mail::to($user->email)->send(new InvoiceEmailManager($array));
            //         unlink($array['file']);
            //     } catch (\Exception $e) {

            //     }
            // }

            // if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value) {
            //     try {
            //         $otpController = new OTPVerificationController;
            //         $otpController->send_order_code($order);
            //     } catch (\Exception $e) {

            //     }
            // }

            //sends email to customer with the invoice pdf attached
            // dd(Config::get('mail.username') != null, $request->session()->get('shipping_info')['email']);
           

            $request->session()->put('order_id', $order->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delete();
            }
            $order->delete();
            flash('Order has been deleted successfully')->success();
        } else {
            flash('Something went wrong')->error();
        }
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        $exploded_ids = explode(",", $ids);
        foreach ($exploded_ids as $dataId) {
            $this->__deleteRelatedOrderDetails($dataId);
        }
        DB::table('orders')->whereIn('id', explode(",", $ids))->delete();
        return response()->json(['success' => "Orders Deleted successfully"]);
    }

    public function bulkInvoiceDownload(Request $request)
    {
        $ids = $request->ids;
        $exploded_ids = explode(",", $ids);
        $orders = Order::whereIn('id', (array) $exploded_ids)->get();
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/'),
        ])->loadView('invoices.multiple_invoice', compact('orders'));
        $path = public_path('invoice/pdf/');
        $filename = 'orderInvoice.pdf';
        $pdf->save($path . $filename);
        $pdf = public_path('invoice/pdf/' . $filename);
        return response()->download($pdf);
        // return response()->json(['success' => 'Invoice downloaded successfully']);
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        //$order->viewed = 1;
        $order->save();
        if(Auth::user()->user_type=='seller'){

            return view('frontend.partials.order_details_seller', compact('order'));
        }
        else{
            return view('frontend.partials.order_details_delivery', compact('order'));

        }
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->save();
        if (Auth::user()->user_type == 'admin') {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        } elseif(Auth::user()->user_type == 'seller'){
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        } elseif(Auth::user()->user_type == 'delivery'){
            foreach ($order->orderDetails->where('order_id', $order->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }
        else {
            foreach ($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }
        if ($order && $orderDetail) {
            try {
                $order_code = $order->code;
                $delivery_status = $orderDetail->delivery_status;
                switch ($delivery_status) {
                    case 'pending':
                        $delivery_stat = 'Pending';
                        break;
                    case 'on_review':
                        $delivery_stat = 'On Review';
                        break;
                    case 'on_delivery':
                        $delivery_stat = 'On Delivery Process';
                        break;
                    case 'delivered':
                        $delivery_stat = 'Delivered';
                        break;
                    default:
                        $delivery_stat = 'On Process';
                        break;
                }

                $data['view'] = 'emails.delivery_status';
                $data['subject'] = 'Order Status - ' . $order_code;
                $data['from'] = Config::get('mail.username');
                $data['content'] = 'Your order ' . $order_code . ' is ' . $delivery_stat;
                $this_user = DB::select('SELECT email FROM users WHERE id = "' . $order->user_id . '"');
                Mail::to($this_user[0]->email)->send(new CustomerEmail($data));
            } catch (\Exception $e) {

            }
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_delivery_status')->first()->value) {
            try {
                $otpController = new OTPVerificationController;
                $otpController->send_delivery_status($order);
            } catch (\Exception $e) {
            }
        }

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'admin') {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }
        elseif(Auth::user()->user_type == 'seller'){

            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        
        } else {
            foreach ($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();

        if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
            if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() == null || !\App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
                if ($order->payment_type == 'cash_on_delivery') {
                    if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if ($orderDetail->product->user->user_type == 'seller') {
                                $seller = $orderDetail->product->user->seller;
                                // $seller->admin_to_pay = $seller->admin_to_pay - ($orderDetail->price * $commission_percentage) / 100;
                                $commission_price=($orderDetail->price * $commission_percentage) / 100;
                                
                                $afterCommissionPrice = $orderDetail->price - ($orderDetail->price * $commission_percentage) / 100;
                                $seller->commission_price = $seller->commission_price + $commission_price;
                                
                                $seller->admin_to_pay = $seller->admin_to_pay + $afterCommissionPrice;
                                $seller->save();
                            }
                        }
                    } else {
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if ($orderDetail->product->user->user_type == 'seller') {
                                $commission_percentage = $orderDetail->product->category->commision_rate;
                                $seller = $orderDetail->product->user->seller;
                                // $seller->admin_to_pay = $seller->admin_to_pay - ($orderDetail->price * $commission_percentage) / 100;
                                $commission_price=($orderDetail->price * $commission_percentage) / 100;

                                $afterCommissionPrice = $orderDetail->price - ($orderDetail->price * $commission_percentage) / 100;
                                $seller->commission_price = $seller->commission_price + $commission_price;

                                $seller->admin_to_pay = $seller->admin_to_pay + $afterCommissionPrice;
                                $seller->save();
                            }
                        }
                    }
                } elseif ($order->manual_payment) {
                    if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if ($orderDetail->product->user->user_type == 'seller') {
                                $seller = $orderDetail->product->user->seller;
                                $commission_price=($orderDetail->price * $commission_percentage) / 100;

                                $seller->commission_price = $seller->commission_price + $commission_price;
                                $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100;
                                $seller->save();
                            }
                        }
                    } else {
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if ($orderDetail->product->user->user_type == 'seller') {
                                $commission_percentage = $orderDetail->product->category->commision_rate;
                                $seller = $orderDetail->product->user->seller;
                                $commission_price=($orderDetail->price * $commission_percentage) / 100;
                                $seller->commission_price = $seller->commission_price + $commission_price;

                                $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100;
                                $seller->save();
                            }
                        }
                    }
                }
            }

            if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliatePoints($order);
            }

            if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {
                $clubpointController = new ClubPointController;
                $clubpointController->processClubPoints($order);
            }

            $order->commission_calculated = 1;
            $order->save();
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_paid_status')->first()->value) {
            try {
                $otpController = new OTPVerificationController;
                $otpController->send_payment_status($order);
            } catch (\Exception $e) {
            }
        }
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {

        $order=Order::findOrFail($request->order_id);
        $order->delivery_id=$request->status;
        $order->save();
    }

    private function __deleteRelatedOrderDetails($id)
    {
        $order = Order::where('id', $id)->with('orderDetails')->first();
        if (!empty($order->orderDetails)) {
            $order->orderDetails()->delete();
        }
    }

    private function __downloadInvoice($id)
    {
        $order = Order::findOrFail($id);
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/'),
        ])->loadView('invoices.multiple_invoice', compact('order'));
        $path = public_path('invoice/pdf/');
        $filename = 'order-' . $order->code . '.pdf';
        $pdf->save($path . $filename);
        $pdf = public_path('invoice/pdf/' . $filename);
        // dd($pdf);
        return response()->download($pdf);
        // return $pdf->download('order-'.$order->code.'.pdf');
    }
}
