<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class DeliveryBoy extends Authenticatable
{
    use Notifiable, HasApiTokens;
    
    protected $fillable = ['first_name','user_id', 'middle_name', 'last_name','email', 'phone_number','avatar',
     'dob', 'blood_group','commission','password', 'active_status', 'availability_status', 'address',
      'city', 'country_id', 'state_id', 'zip_code', 'vechile_name', 'owner_name', 'vechile_color',
       'vechile_registration_no', 'vechile_details','driving_license_no', 'vechile_rc_book_no','account_name',
        'account_number','gpay_number','bank_address','ifsc_code', 'branch_name'];


    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'id');
    }

    public function getAvatar($img)
    {
        if ($img != null && $img != "default.png") {
            return asset("deliveryboy/avatar/" . $img);
        }
        return asset("images/default.png");
    }

    public function getLicense($img)
    {
        if ($img != null) {
            return asset("deliveryboy/driving_license/" . $img);
        }
        return asset("images/default.png");
    }

    public function getRc($img)
    {
        if ($img != null) {
            return asset("deliveryboy/rc_book/" . $img);
        }
        return asset("images/default.png");
    }
}
