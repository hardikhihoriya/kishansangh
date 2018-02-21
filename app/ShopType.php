<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ShopType;
use Config;
use DB;

class ShopType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table= "shop_type";

    public static function getShopType() {
        return ShopType::where('status', '<>',Config::get('constant.DELETED_FLAG'))->get();
    }
    
    public function scopeSearchShopTypeName($query, $value) {
        return $query->Where('shop_type_name' , 'LIKE' , "%$value%");
    }

    public function scopeSearchShopTypeDetail($query, $value) {
        return $query->orWhere('shop_type_detail', 'LIKE', "%$value%");
    }
    
    public function scopeSearchShopTypeStatus($query, $value) {
        return $query->orWhere('status', 'LIKE', "%$value%");
    }

    public static function updateShopTypeStatus($id) {
        try {
            DB::statement(
                    DB::raw("UPDATE shop_type SET status =
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
