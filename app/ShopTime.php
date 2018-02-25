<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Shop;

class ShopTime extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = "shop_time";

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

}
