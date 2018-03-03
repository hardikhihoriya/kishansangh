<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\InvestmentPackage;
use Redirect;
use Response;
use DB;

class InvestmentPackageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    public function index() {
        return view('admin.investment-package.list');
    }

    /**
     * [listAjax List InvestmentPackage]
     * @param  [type]       [description]
     * @return [json]       [list of investment-package]
     */
    public function listAjax(Request $request) {
        $records = array();
        //processing custom actions
        if ($request->customActionType == 'groupAction') {

            $action = $request->customActionName;
            $idArray = $request->id;

            if ($action == 'delete') {
                
            }
            if ($action == 'status') {
                foreach ($idArray as $_idArray) {
                    InvestmentPackage::updateInvestmentPackageStatus($_idArray);
                }
                $records["customMessage"] = trans('adminmsg.INVESTMENT_PACKAGE_STATUS_UPDATED_SUCCESS');
            }
        }

        $columns = array(
            0 => 'investment_package_name',
            1 => 'investment_package_year',
            2 => 'investment_package_size',
            3 => 'investment_package_duration',
            4 => 'investment_package_price',
            5 => 'status'
        );

        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();

        //getting records from the investment-package table
        $iTotalRecords = InvestmentPackage::getInvestmentPackageCount();
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $records["data"] = InvestmentPackage::where('status', '<>', Config::get('constant.DELETED_FLAG'));

        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchInvestmentPackageName($val)
                        ->SearchInvestmentPackageYear($val)
                        ->SearchInvestmentPackageSize($val)
                        ->SearchInvestmentPackageDuration($val)
                        ->SearchInvestmentPackagePrice($val)
                        ->SearchInvestmentPackageStatus($val);
            });

            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                $query->SearchInvestmentPackageName($val)
                        ->SearchInvestmentPackageYear($val)
                        ->SearchInvestmentPackageSize($val)
                        ->SearchInvestmentPackageDuration($val)
                        ->SearchInvestmentPackagePrice($val)
                        ->SearchInvestmentPackageStatus($val);
                    })->count();
        }

        //order by
        foreach ($order as $o) {
            $records["data"] = $records["data"]->orderBy($columns[$o['column']], $o['dir']);
        }

        //limit
        $records["data"] = $records["data"]->take($iDisplayLength)->offset($iDisplayStart)->get([
            'id',
            'investment_package_name',
            'investment_package_year',
            'investment_package_size',
            'investment_package_duration',
            'investment_package_price',
            'status'
        ]);

        if (!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit = route('investment-package.edit', $_records->id);

                if ($_records->status == "active") {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Inactive" class="btn-status-investment-package" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                } else {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Active" class="btn-status-investment-package" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                }
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Investment Package' ><span class='glyphicon glyphicon-edit'></span></a>
                                                    &emsp;<a href='javascript:;' data-id='" . $_records->id . "' class='btn-delete-investment-package' title='Delete Investment Package' ><span class='glyphicon glyphicon-trash'></span></a>";
            }
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalFiltered;

        return Response::json($records);
    }

    /**
     * Create controller
     */
    public function create() {
        return $this->_update();
    }

    /**
     * Update controller
     * @param $id = investment-package id
     */
    public function update($id) {
        $investmentPackage = InvestmentPackage::find($id);
        if (!$investmentPackage) {
            return Redirect::to("/admin/investment-package/")->with('error', trans('adminmsg.INVESTMENT_PACKAGE_NOT_EXIST'));
        }
        return $this->_update($investmentPackage);
    }

    /**
     * Create/Update controller
     * @param $investment-package = investment-package object
     */
    private function _update($investmentPackage = null) {
        if ($investmentPackage === null) {
            $investmentPackage = new InvestmentPackage;
        }

        $status = Config::get('constant.STATUS');
        return view('admin.investment-package.add', compact('investmentPackage', 'status'));
    }

    /**
     * @description: save investment-package data
     * @return type
     */
    public function set(Request $request) {
        try {
            $rule = [
                'investment_package_name' => 'required|max:100',
                'investment_package_year' => 'required|integer',
                'investment_package_size' => 'required|integer|min:1|max:25',
                'investment_package_duration' => 'required|max:500',
                'investment_package_description' => 'required|max:500',
                'investment_package_price' => 'required|',
                'status' => 'required'
            ];

            $this->validate(request(), $rule);

            DB::beginTransaction();
            $investmentPackage = InvestmentPackage::find($request->id);

            $postData = $request->only('investment_package_name', 'investment_package_year', 'investment_package_size', 'investment_package_duration', 'investment_package_description', 'investment_package_price', 'status');

            if (isset($request->id) && $request->id > 0) {
                $investmentPackage->update(array_filter($postData));
                $investmentPackage->save();
                DB::commit();
                return Redirect::to("/admin/investment-package/")->with('success', trans('adminmsg.INVESTMENT_PACKAGE_UPDATED_SUCCESS_MSG'));
            } else {
                $investmentPackage = new InvestmentPackage(array_filter($postData));
                $investmentPackage->save();
                
                DB::commit();
                return Redirect::to("/admin/investment-package/")->with('success', trans('adminmsg.INVESTMENT_PACKAGE_CREATED_SUCCESS_MSG'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/investment-package/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));
        }
    }

}
