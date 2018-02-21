<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ShopMarketing;
use Config;
use DB;

class ShopMarketing extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = "shop_marketing";

    public static function getShopMarketing() {
        return ShopMarketing::where('status', '<>', Config::get('constant.DELETED_FLAG'))->get();
    }

    public function scopeSearchShopMarketingName($query, $value) {
        return $query->Where('shop_marketing_name', 'LIKE', "%$value%");
    }

    public function scopeSearchShopMarketingMember($query, $value) {
        return $query->orWhere('member', 'LIKE', "%$value%");
    }

    public function scopeSearchShopMarketingDetail($query, $value) {
        return $query->orWhere('description', 'LIKE', "%$value%");
    }

    public function scopeSearchShopMarketingStatus($query, $value) {
        return $query->orWhere('status', 'LIKE', "%$value%");
    }

    public static function updateShopMarketingStatus($id) {
        try {
            DB::statement(
                    DB::raw("UPDATE shop_marketing SET status =
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
