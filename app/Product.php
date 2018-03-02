<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use Config;
use DB;

class Product extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = "product";

    public static function getProduct() {
        return Product::where('status', '<>', Config::get('constant.DELETED_FLAG'))->get();
    }

    public static function getProductCount() {
        return Product::where('status', '<>', Config::get('constant.DELETED_FLAG'))->count();
    }

    public function scopeSearchProductName($query, $value) {
        return $query->Where('product_name', 'LIKE', "%$value%");
    }

    public function scopeSearchProductType($query, $value) {
        return $query->orWhere('product_type', 'LIKE', "%$value%");
    }

    public function scopeSearchProductYear($query, $value) {
        return $query->orWhere('product_year', 'LIKE', "%$value%");
    }

    public function scopeSearchProductStatus($query, $value) {
        return $query->orWhere('status', 'LIKE', "%$value%");
    }

    public static function updateProductStatus($id) {
        try {
            DB::statement(
                    DB::raw("UPDATE product SET status =
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
