<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\InvestmentPackage;
use Config;
use DB;

class InvestmentPackage extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = "investment_package";

    public static function getInvestmentPackage() {
        return InvestmentPackage::where('status', '<>', Config::get('constant.DELETED_FLAG'))->get();
    }

    public static function getInvestmentPackageCount() {
        return InvestmentPackage::where('status', '<>', Config::get('constant.DELETED_FLAG'))->count();
    }

    public function scopeSearchInvestmentPackageName($query, $value) {
        return $query->Where('investment_package_name', 'LIKE', "%$value%");
    }

    public function scopeSearchInvestmentPackageYear($query, $value) {
        return $query->orWhere('investment_package_year', 'LIKE', "%$value%");
    }

    public function scopeSearchInvestmentPackageSize($query, $value) {
        return $query->orWhere('investment_package_size', 'LIKE', "%$value%");
    }

    public function scopeSearchInvestmentPackageDuration($query, $value) {
        return $query->orWhere('investment_package_duration', 'LIKE', "%$value%");
    }

    public function scopeSearchInvestmentPackagePrice($query, $value) {
        return $query->orWhere('investment_package_price', 'LIKE', "%$value%");
    }

    public function scopeSearchInvestmentPackageStatus($query, $value) {
        return $query->orWhere('status', 'LIKE', "%$value%");
    }

    public static function updateInvestmentPackageStatus($id) {
        try {
            DB::statement(
                    DB::raw("UPDATE investment_package SET status =
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
