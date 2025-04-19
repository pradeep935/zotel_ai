<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

use Response, Auth;

class PurchaseController extends Controller
{
	public function index(){
		return Inertia::render('WorkOrder/Index')->withViewData(['sidebar' => 'purchase_orders']);
	}

	public function getData(Request $request){
		$page_no = $request->page_no;
		$max_per_page = $request->max_per_page;

		$workOrders = DB::table("purchase_order")->select("purchase_order.id","purchase_order.company_id","purchase_order.file_upload","purchase_order.purchase_order_no","purchase_order.date","companies.company_name")->leftJoin("companies","companies.id","=","purchase_order.company_id")->where("purchase_order.client_id", Auth::user()->client_id);

		if ($request->company_id) {
			$workOrders->where("purchase_order.company_id", $request->company_id);
		}

		if($request->purchase_order_no){
			$workOrders->where("purchase_order.purchase_order_no", $request->purchase_order_no);
		}

		$data["total"] = $workOrders->count();

		$workOrders = $workOrders->skip(($page_no-1)*$max_per_page)->take($max_per_page)->get();

		$companies = DB::table("companies")->select("id as value", "company_name as label")->where("client_id", Auth::user()->client_id)->get();

		$data["success"] = true;
		$data["workOrders"] = $workOrders;
		$data["companies"] = $companies;

		return response()->json($data);
	}

	public function storeData(Request $request){
		$cre =[
			"company_id" => $request->company_id,
			"purchase_order_no" => $request->purchase_order_no,
			"date" => $request->date,
		];

		$rules =[
			"company_id" => "required",
			"purchase_order_no" => "required",
			"date" => "required",
		];

		$validator = Validator::make($cre, $rules);

		if ($validator->passes()) {
			if ($request->id) {
				$check = DB::table("purchase_order")->where("id",$request->id)->where("client_id",Auth::user()->client_id)->first();
				if (!$check) {
					$data['success'] = false;
                    $data['message'] = "Invalid Request";
                    return response()->json($data);
				} else{
					DB::table("purchase_order")->where("id",$request->id)->update([
						"company_id" => $request->company_id,
						"purchase_order_no" => $request->purchase_order_no,
						"date" => $request->date,
						"file_upload" => $request->file_upload
					]);

					$data["success"] = true;
					$data["message"] = "Data Successfully Updated";
				}
			} else {
				DB::table("purchase_order")->insert([
					"company_id" => $request->company_id,
					"purchase_order_no" => $request->purchase_order_no,
					"date" => $request->date,
					"file_upload" => $request->file_upload,
					"client_id" => Auth::user()->client_id,
					"created_at" => date('Y-m-d H:i:s')
				]);

				$data["success"] = true;
				$data["message"] = "Data Successfully Submitted";
			}
		} else {
			$data["success"] = false;
			$data["message"] = $validator->errors()->first();
		}

		return response()->json($data);
	}

	public function forEdit($id){
		$workOrders = DB::table("purchase_order")->where("id",$id)->where("client_id",Auth::user()->client_id)->first();

		$data["success"] = true;
		$data["workOrders"] = $workOrders;

		return response()->json($data);
	}	

	public function deleteWorkOrder($id){	
		DB::table("purchase_order")->where("id",$id)->where("client_id",Auth::user()->client_id)->delete();

		$data["success"] = true;
		$data["message"] = "Data Deleted Successfully";

		return response()->json($data);

	}
}