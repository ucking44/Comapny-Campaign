<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\lslPointsAwardTransaction;

class TestController extends Controller
{
    public function testApp()
    {
        $testing = Http::get("http://localhost:8000/api/authors");
        //$testing = Http::get("https://jsonplaceholder.typicode.com/posts");
        return $testing->json();
    }

    public function index(Request $request)
    {
        //$apiURL = 'https://api.mywebtuts.com/api/users';
        //$apiURL = 'https://jsonplaceholder.typicode.com/posts';
        //$apiURL = 'http://localhost:8000/api/authors';
        $apiURL = 'https://rewardsbox.perxclm.com/rewardsbox_app/v1/api/v1/?api=view_events';
        // $postInput = [

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "your api goes here",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer 13b196c5b4060ad51e269b0471ab935c"
        //"Authorization: Bearer eyJ0eciOiJSUzI1NiJ9.eyJMiIsInNjb3BlcyI6W119.K3lW1STQhMdxfAxn00E4WWFA3uN3iIA"
        ),
        ));

        $response = curl_exec($curl);
        $data = json_decode($response, true);

        echo $data;

        //$token = 'Authorization: Bearer 13b196c5b4060ad51e269b0471ab935c'

    }

    public function savePointAwardTransaction(Request $request)
    {
        $membership_id = DB::table('lsl_enrollment_master')
                           ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Last_name', 'lsl_enrollment_master.Membership_id')
                           ->where(['Membership_id' => $request->membership_id])
                           ->first();

        $transactionType = DB::table('lsl_transaction_type_master')
                           ->select('lsl_transaction_type_master.Transaction_id', 'lsl_transaction_type_master.Transaction_type_name')
                           //->where(['Membership_id' => $request->membership_id])
                           ->get();

        $paymentType = DB::table('lsl_payment_type_master')
                           ->select('lsl_payment_type_master.payment_id', 'lsl_payment_type_master.payment_type_name')
                           ->get();

        $lslBranchMaster = DB::table('lsl_branch_master')
                           ->select('lsl_branch_master.branch_id', 'lsl_branch_master.branch_name')
                           ->get();
        dd($membership_id, $transactionType, $paymentType, $lslBranchMaster);  /// Create_User_id
        //dd([$membership_id->First_name, (int)$membership_id->Membership_id], $transactionType, $paymentType, $lslBranchMaster);  /// Create_User_id

        $this->validate($request, [
            'membership_id' => 'required',
            'price' => 'required'
        ]);

        $pointAwardTransaction = new lslPointsAwardTransaction();
        $pointAwardTransaction->membership_id = intVal($membership_id->Membership_id);
        //$pointAwardTransaction->transaction_type = $request->transaction_type;
        // if(isset($request->transaction_type))
        // {
        //     $pointAwardTransaction->transaction_type = 'Transaction With Loyalty';
        // } else {
        //     $pointAwardTransaction->transaction_type = 'No Transaction Was Selected';
        // }

        $pointAwardTransaction->item = $transactionType->Transaction_type_name;
        $pointAwardTransaction->item = $request->item;
        //$pointAwardTransaction->item = $request->item;  transaction_type

        // if(isset($request->item))
        // {
        //     $pointAwardTransaction->item = 'Spend for loyalty: 0001';
        // } else {
        //     $pointAwardTransaction->item = 'No Item Was Selected';
        // }
        $pointAwardTransaction->price = (int)$request->price;
        $pointAwardTransaction->total_amount = $request->price + $request->quantity;
        dd($pointAwardTransaction);

    }

    // $lslItem = DB::table('lsl_pos_inventory_master')
        //                    ->join('lsl_enrollment_master', 'lsl_pos_inventory_master.Create_User_id', '=', 'lsl_enrollment_master.Enrollment_id')
        //                    //->where(['Membership_id' => $request->membership_id])
        //                    ->select('lsl_pos_inventory_master.item_id', 'lsl_pos_inventory_master.Item_name')
        //                    ///////////// BELOW WHERE STATEMENT TO BE USED WHEN USER LOGIN HAS BEEN IMPLEMENTED INTO THE SYSTEM ////
        //                    ->where('lsl_pos_inventory_master.Create_User_id', '=', 'lsl_enrollment_master.Enrollment_id')
        //                    //->get();
        //                    ->first();

    // "transaction_type_id",
    // "membership_id",
    // "sold_by_id",
    // "branch_id",
    // "payment_type_id",
    // "gift_card_id",
    // "purchase_gift_card",
    // "item_id",
    // "item",
    // "quantity",
    // "price",
    // "total_amount",
    // "total_vat_amount",
    // "balance_to_pay",
    // "redeem_point",
    // "redeem_amount",
    // "gift_card",
    // "total_balance_to_pay",
    // "branch_user_pin_id",
    // "remark"


        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        // CURLOPT_URL => "your api goes here",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => "",
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array(
        // "Authorization: Bearer eyJ0eciOiJSUzI1NiJ9.eyJMiIsInNjb3BlcyI6W119.K3lW1STQhMdxfAxn00E4WWFA3uN3iIA"
        // ),
        // ));

        // $response = curl_exec($curl);
        // $data = json_decode($response, true);

        // echo $data;

        public function saveManualCreditAdd(Request $request, $id)
        {
            try {
                    DB::beginTransaction();
    
                    $membershipId = DB::table('lsl_enrollment_master')
                                      ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', '=', 'lsl_company_master.Company_id')
                                    //->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Last_name', 'lsl_enrollment_master.Membership_id', 'lsl_enrollment_master.Pin', 'lsl_enrollment_master.Current_balance', 'lsl_enrollment_master.Total_redeem_points', 'lsl_enrollment_master.Total_purchase_amount', 'lsl_enrollment_master.Company_id')
                                    //->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Last_name', 'lsl_enrollment_master.Membership_id', 'lsl_enrollment_master.Pin', 'lsl_enrollment_master.Current_balance', 'lsl_enrollment_master.Total_redeem_points', 'lsl_enrollment_master.Total_purchase_amount', 'lsl_enrollment_master.Company_id')
                                      ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Last_name', 'lsl_enrollment_master.Membership_id', 'lsl_enrollment_master.Pin', 'lsl_company_master.Current_balance', 'lsl_enrollment_master.balance', 'lsl_enrollment_master.Total_redeem_points', 'lsl_enrollment_master.Total_purchase_amount', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                                      ->where(['Enrollment_id' => $id])
                                    //->orWhere(['lsl_company_master.Company_id' => 'lsl_enrollment_master.Company_id'])
                                      ->first();
    
                    //dd($membershipId);
                    $deductFundsFromCompanyMaster = DB::table('lsl_company_master')
                                                        ->select('lsl_company_master.Company_id', 'lsl_company_master.Current_balance')
                                                        ->first();
    
                    //dd($deductFundsFromCompanyMaster);
                    $this->validate($request, [
                        'credit_point' => 'required',
                        'remark' => 'required'
                    ]);
    
                    $manualCreditTransaction = LslEnrollmentMaster::findOrFail($id);
                    $manualCreditTransaction->balance = $request->credit_point + $membershipId->balance;
                    //$manualCreditTransaction->Company_id = $membershipId->Company_id;
                    $manualCreditTransaction->Communication_flag_remarks = $request->remark;
                    if($membershipId->Current_balance > $request->credit_point)
                    {
                        $manualCreditTransaction->save();
    
                    //dd($manualCreditTransaction);
                    //$manualCreditTransaction->save();
                    //dd($manualCreditTransaction);
    
                    $deductFundsFromCompanyMaster = DB::table('lsl_company_master')
                                                        ->select('lsl_company_master.Company_id', 'lsl_company_master.Current_balance')
                                                        ->where(['lsl_company_master.Company_id' => $manualCreditTransaction->Company_id])
                                                        ->where('lsl_company_master.Current_balance', '>', $request->credit_point)
                                                        //->where('lsl_company_master.Company_id', $membershipId->Current_balance > $request->credit_point)
                                                        ->update(['lsl_company_master.Current_balance' => $membershipId->Current_balance - $request->credit_point]);
    
                    DB::commit();
    
                    return response()->json([
                        'success' => true,
                        'data' => $manualCreditTransaction
                    ], 201);
    
                }
                else {
                    dd("Hello World");
                }
                }
                catch (Exception $e)
                {
                    DB::rollback();
                    throw $e;
                }
        }

}
