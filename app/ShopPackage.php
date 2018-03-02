<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ShopPackage;
use Config;
use DB;

class ShopPackage extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = "shop_package";

    public static function getShopPackage() {
        return ShopPackage::where('status', '<>', Config::get('constant.DELETED_FLAG'))->get();
    }

    public static function getShopPackageCount() {
        return ShopPackage::where('status', '<>', Config::get('constant.DELETED_FLAG'))->count();
    }
    
    public function scopeSearchShopPackageName($query, $value) {
        return $query->Where('package_name', 'LIKE', "%$value%");
    }

    public function scopeSearchBoostingPoint($query, $value) {
        return $query->orWhere('boosting_point', 'LIKE', "%$value%");
    }

    public function scopeSearchPerDaySMS($query, $value) {
        return $query->orWhere('per_day_SMS', 'LIKE', "%$value%");
    }

    public function scopeSearchDescription($query, $value) {
        return $query->orWhere('package_description', 'LIKE', "%$value%");
    }

    public function scopeSearchPrice($query, $value) {
        return $query->orWhere('price', 'LIKE', "%$value%");
    }

    public function scopeSearchStatus($query, $value) {
        return $query->orWhere('status', 'LIKE', "%$value%");
    }

    public static function updateShopPackageStatus($id) {
        try {
            DB::statement(
                    DB::raw("UPDATE shop_package SET status =
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

}
