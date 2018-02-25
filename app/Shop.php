<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Shop;
use Config;
use DB;

class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = "shop";

    public static function getShop() {
        return Shop::where('status', '<>', Config::get('constant.DELETED_FLAG'))->get();
    }

    public function scopeSearchShopRegistrationNo($query, $value) {
        return $query->Where('shop_registration_no', 'LIKE', "%$value%");
    }

    public function scopeSearchUserName($query, $value) {
        return $query->orWhere(DB::raw('CONCAT(first_name," ", middle_name," ", last_name)') , 'LIKE' , "%$value%");
    }

    public function scopeSearchVendorName($query, $value) {
        return $query->orWhere('vendor_name', 'LIKE', "%$value%");
    }

    public function scopeSearchShopType($query, $value) {
        return $query->orWhere('shop_type_name', 'LIKE', "%$value%");
    }

    public function scopeSearchShopPackage($query, $value) {
        return $query->orWhere('package_name', 'LIKE', "%$value%");
    }

    public function scopeSearchShopName($query, $value) {
        return $query->orWhere('shop_name', 'LIKE', "%$value%");
    }

    public function scopeSearchShopEmail($query, $value) {
        return $query->orWhere('shop_email', 'LIKE', "%$value%");
    }

    public function scopeSearchShopPhone($query, $value) {
        return $query->orWhere('shop_phone_no', 'LIKE', "%$value%");
    }

    public function scopeSearchShopAnniverssaryDate($query, $value) {
        return $query->orWhere('shop_anniversary_date', 'LIKE', "%$value%");
    }

    public function scopeSearchShopZipcode($query, $value) {
        return $query->orWhere('zipcode', 'LIKE', "%$value%");
    }

    public function scopeSearchShopStatus($query, $value) {
        return $query->orWhere('status', 'LIKE', "%$value%");
    }

    public static function updateShopStatus($id) {
        try {
            DB::statement(
                    DB::raw("UPDATE shop SET status =
                            (
                                CASE
                                    WHEN status='active'
                                    THEN 'inactive'
                                    ELSE 'active'
                                END
                            )
                            WHERE id='" . $id . "'"
                    ), array()
            );
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    public function generateRegistrationNo() {
        return str_random(15) . date('H'). str_random(15) . date('i'). str_random(15) . date('s') . microtime(true);
    }
    
    public function shopTime() {
        return $this->hasMany(ShopTime::class, 'shop_id');
    }

}
