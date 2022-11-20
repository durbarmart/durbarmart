<?php

namespace App;

use App\Product;
use App\User;
use App\Seller;
use App\Shop;
// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class SellersImport implements ToCollection, WithHeadingRow, WithValidation,SkipsOnFailure 
{


    public function __construct()
    {
    }
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
    public function collection(Collection $row)
    {   
        $vendor_exists = 0;

        foreach($row as $c => $d){
            $user = [
                'name' => '',
                'phone' => '',
                'user_type' => 'seller',
                'email' => '',
                'email_verified_at' => Carbon::now()
            ];
            $seller = [
                'user_id' => '',
                'verification_status' => 1,

            ];
            $shop = [
                'name' => '',
                'address' => '',
                'user_id' => '',
                'slug' => '',
                'meta_title' => '',
                'meta_description' => ''
            ];
            // dd($d);
            if(($d['vendor_name'] != '') || ($d['vendor_shop_name'] != '') || ($d['contact'] != '') || ($d['mail'] != '') || ($d['address'] != '')){
                
            // foreach($d as $a => $b){
           
             


        if($d['vendor_name'] != ''){
        //     $user['name'] = 'empty';
        // }else{
            $user['name'] = trim($d['vendor_name']);
        }


        if($d['vendor_shop_name'] == ''){
            $shop['name'] = 'empty';
            $shop['meta_title'] = 'empty';
            $shop['meta_description'] = 'empty';
            $shop['slug'] = 'empty';
        }
        else{
            $shop['name'] = trim($d['vendor_shop_name']);
            $shop['meta_title'] = trim($d['vendor_shop_name']);
            $shop['meta_description'] = trim($d['vendor_shop_name']);
            
            $x = str_replace(')','',$d['vendor_shop_name']);

            $y = str_replace('-',' ',$x);
            
            $z = (preg_replace('/[^A-Za-z0-9\-]/', '', $y));
            
            $w = preg_replace('!\s+!', ' ', $z);

            $shop['slug'] = str_replace(' ','-',strtolower(trim($w)));
            if($d['vendor_name'] == ''){
                $user['name'] = trim($d['vendor_shop_name']);
            }
        }

        if($d['contact'] != ''){
            $user['phone'] = trim($d['contact']);
            
        }

        if($d['mail'] != ''){
            $user['email'] = trim($d['mail']);            
        }
        else{
            $user['email'] = null;
        }

        if($d['address'] != ''){
            $shop['address'] = trim($d['address']);                        
        }
        $user_email = User::where('email',trim($user['email']))->count();

            if($user_email > 0){
                $user['email'] = null;
            }
            $user_check = User::where('name',trim($user['name']))->count();
            if($user_check == 0){
                $user_create = User::create($user);
            }else{
                $user_id = User::where('name',$user['name'])->update([
                    'phone' => $user['phone'],
                    'email' => $user['email'],
                ]);
            }
            $user_id = User::where('name',$user['name'])->first();

            $seller_check = Seller::where('user_id',$user_id->id)->count();

            if($seller_check == 0){
                $seller['user_id'] = $user_id->id;
                $seller_create = Seller::create($seller);
            }
            
            $shop_check = Shop::where('user_id',$user_id->id)->count();

            if($shop_check == 0){
                $shop['user_id'] = $user_id->id;

                if($shop['slug'] == 'empty'){
                    $shop['slug'] = 'empty-'.$user_id;
                }

                $shop_create = Shop::create($shop);
            }else{
                $user_id = Shop::where('user_id',$user_id->id)->update([
                    'address' =>  $shop['address']
                ]);
            }
        }
        }
        return true;
    }

    public function rules(): array
    {
        return [
             'unit_price' => function($attribute, $value, $onFailure) {
                  if (!is_numeric($value)) {
                       $onFailure('Unit price is not numeric');
                  }
              }
        ];
    }
}