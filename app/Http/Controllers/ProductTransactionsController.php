<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\lslBranchMaster;
use App\Models\LslPartnerMaster;
use App\Models\LslPartnerCategory;
use Illuminate\Support\Facades\DB;
use App\Models\LslSweepstakeMaster;
use App\Models\LslPosInventoryMaster;
use App\Models\LslProductBrandMaster;
use App\Models\LslProductGroupMaster;
use App\Models\LslLoyaltyProgramMaster;
use App\Models\LslCompanyTransactionChannelMaster;

class ProductTransactionsController extends Controller
{
    public function getAllLoyaltyProgram()
    {
        return response()->json([
            'success' => true,
            'data' => LslLoyaltyProgramMaster::get()
        ]);
    }

    public function getAllBranches()
    {
        return response()->json([
            'success' => true,
            'data' => lslBranchMaster::get()
        ]);
    }

    public function getSweepstake()
    {
        $allSweepstake = LslSweepstakeMaster::all();

        return response()->json([
            'status' => true,
            'total'  => count($allSweepstake),
            'data'   => $allSweepstake
        ]);
    }

    public function getAllProductGroup()
    {
        return response()->json([
            'success' => true,
            'data' => LslProductGroupMaster::get()
        ]);
    }

    public function getAllProductBrand()
    {
        return response()->json([
            'success' => true,
            'data' => LslProductBrandMaster::get()
        ]);
    }

    public function getAllPosInventory()
    {
        return response()->json([
            'success' => true,
            'data' => LslPosInventoryMaster::get()
        ]);
    }

    public function saveProductGroup(Request $request)
    {
        $enrollID = DB::table('lsl_enrollment_master')
                      ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                      ->select('lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id')
                      ->where('lsl_company_master.Company_id', '=', $request->Company_id)
                      ->where('lsl_enrollment_master.Membership_id', '=', $request->Membership_id)
                      ->first();
        //dd($enrollID);
        $this->validate($request, [
            'Product_group_code' => 'required',
            'Product_group_name' => 'required'
        ]);

        $productGroup = new LslProductGroupMaster();
        $productGroup->Company_id = $enrollID->Company_id;
        $productGroup->Create_User_id = $enrollID->Enrollment_id;
        //$productGroup->Update_User_id = $enrollID->Enrollment_id;
        $productGroup->Product_group_code = $request->Product_group_code;
        $productGroup->Product_group_name = $request->Product_group_name;
        $productGroup->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Group Created Successfully!',
            'data'    => $productGroup
        ]);
    }

    public function saveProductBrand(Request $request)
    {
        $enrollID = DB::table('lsl_product_group_master')
                      ->join('lsl_company_master', 'lsl_product_group_master.Company_id', 'lsl_company_master.Company_id')
                      ->join('lsl_enrollment_master', 'lsl_product_group_master.Create_User_id', 'lsl_enrollment_master.Enrollment_id')
                      ->select('lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_product_group_master.Product_group_id', 'lsl_product_group_master.Product_group_code', 'lsl_product_group_master.Product_group_name')
                      ->where('lsl_company_master.Company_id', '=', $request->Company_id)
                      ->where('lsl_enrollment_master.Membership_id', '=', $request->Membership_id)
                      ->where('lsl_product_group_master.Product_group_id', '=', $request->Product_group_id)
                      ->first();
        //dd($enrollID);
        $this->validate($request, [
            'Product_brand_code' => 'required'
        ]);

        $productBrand = new LslProductBrandMaster();
        $productBrand->Company_id = $enrollID->Company_id;
        $productBrand->Create_User_id = $enrollID->Enrollment_id;
        $productBrand->Product_group_code = $enrollID->Product_group_id;
        $productBrand->Product_brand_code = $request->Product_brand_code;
        $productBrand->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Brand Created Successfully!',
            'data'    => $productBrand
        ]);
    }

    public function savePosInventory(Request $request)
    {
        $enrollID = DB::table('lsl_product_brand_master')
                        ->join('lsl_company_master', 'lsl_product_brand_master.Company_id', 'lsl_company_master.Company_id')
                        ->join('lsl_codedecode_master', 'lsl_company_master.Solution_type', 'lsl_codedecode_master.Code_id')
                        ->join('lsl_codedecode_type_master', 'lsl_codedecode_master.Typecd_id', 'lsl_codedecode_type_master.Typecd_id')
                        ->join('lsl_enrollment_master', 'lsl_product_brand_master.Create_User_id', 'lsl_enrollment_master.Enrollment_id')
                        ->join('lsl_product_group_master', 'lsl_product_brand_master.Product_group_code', 'lsl_product_group_master.Product_group_id')
                        ->select('lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_product_group_master.Product_group_id', 'lsl_product_group_master.Product_group_code', 'lsl_product_group_master.Product_group_name', 'lsl_product_brand_master.Product_brand_id', 'lsl_product_brand_master.Product_brand_code', 'lsl_codedecode_type_master.Typecd_id', 'lsl_codedecode_type_master.Typecd_description', 'lsl_codedecode_master.Code_id', 'lsl_codedecode_master.Decode_description')
                        ->where('lsl_company_master.Company_id', '=', $request->Company_id)
                        ->where('lsl_enrollment_master.Membership_id', '=', $request->Membership_id)
                        ->where('lsl_product_group_master.Product_group_id', '=', $request->Product_group_id)
                        ->where('lsl_product_brand_master.Product_brand_id', '=', $request->Product_brand_id)
                        ->where(['lsl_codedecode_master.Typecd_id' => 6])
                        ->first();
            //dd($enrollID);
        $this->validate($request, [
            'Item_code' => 'required',
            'Item_name' => 'required',
            'Item_price' => 'required',
            'Threshold_balance' => 'required',
            'Current_balance' => 'required',
            //'Item_vat' => 'required'
        ]);

        $posInventory = new LslPosInventoryMaster();
        $posInventory->Company_id = $enrollID->Company_id;
        $posInventory->Create_User_id = $enrollID->Enrollment_id;
        $posInventory->Product_group_code = $enrollID->Product_group_id;
        $posInventory->Product_brand_code = $enrollID->Product_brand_id;
        $posInventory->Item_code = $request->Item_code;
        $posInventory->Item_name = $request->Item_name;
        $posInventory->Item_price = (int)$request->Item_price;
        $posInventory->Threshold_balance = (int)$request->Threshold_balance;
        $posInventory->Current_balance = (int)$request->Current_balance;
        $posInventory->Item_vat = (int)$request->Item_vat;
        $posInventory->save();

        return response()->json([
            'success' => true,
            'message' => 'Pos Inventory Created Successfully!',
            'data'    => $posInventory
        ]);
    }

    public function updatePosInventory(Request $request, $id)
    {
        $enrollID = DB::table('lsl_product_brand_master')
                      ->join('lsl_company_master', 'lsl_product_brand_master.Company_id', 'lsl_company_master.Company_id')
                      ->join('lsl_codedecode_master', 'lsl_company_master.Solution_type', 'lsl_codedecode_master.Code_id')
                      ->join('lsl_codedecode_type_master', 'lsl_codedecode_master.Typecd_id', 'lsl_codedecode_type_master.Typecd_id')
                      ->join('lsl_enrollment_master', 'lsl_product_brand_master.Create_User_id', 'lsl_enrollment_master.Enrollment_id')
                      ->join('lsl_product_group_master', 'lsl_product_brand_master.Product_group_code', 'lsl_product_group_master.Product_group_id')
                      ->select('lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_product_group_master.Product_group_id', 'lsl_product_group_master.Product_group_code', 'lsl_product_group_master.Product_group_name', 'lsl_product_brand_master.Product_brand_id', 'lsl_product_brand_master.Product_brand_code', 'lsl_codedecode_type_master.Typecd_id', 'lsl_codedecode_type_master.Typecd_description', 'lsl_codedecode_master.Code_id', 'lsl_codedecode_master.Decode_description')
                      ->where('lsl_company_master.Company_id', '=', $request->Company_id)
                      ->where('lsl_enrollment_master.Membership_id', '=', $request->Membership_id)
                      ->where('lsl_product_group_master.Product_group_id', '=', $request->Product_group_id)
                      ->where('lsl_product_brand_master.Product_brand_id', '=', $request->Product_brand_id)
                      ->where(['lsl_codedecode_master.Typecd_id' => 6])
                      ->first();
        //dd($enrollID);
        $updatedPosInventory = LslPosInventoryMaster::findOrFail($id);
        $updatedPosInventory->Company_id = $enrollID->Company_id;
        $updatedPosInventory->Create_User_id = $enrollID->Enrollment_id;
        $updatedPosInventory->Product_group_code = $enrollID->Product_group_id;
        $updatedPosInventory->Product_brand_code = $enrollID->Product_brand_id;
        $updatedPosInventory->Item_code = $request->Item_code;
        $updatedPosInventory->Item_name = $request->Item_name;
        $updatedPosInventory->Item_price = (int)$request->Item_price;
        $updatedPosInventory->Threshold_balance = (int)$request->Threshold_balance;
        $updatedPosInventory->Current_balance = (int)$request->Current_balance;
        $updatedPosInventory->Item_vat = (int)$request->Item_vat;
        $updatedPosInventory->save();

        return response()->json([
            'success' => true,
            'message' => 'Pos Inventory Updated Successfully!',
            'data'    => $updatedPosInventory
        ]);
    }

    public function deletePosInventory($id)
    {
        $deletePosInv = LslPosInventoryMaster::findOrFail($id);
        $deletePosInv->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pos Inventory Delete Successfully!',
        ]);
    }

    public function saveTransactionChannel(Request $request)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_company_master.Company_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        //->where(['Enrollment_id' => $request->Enrollment_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->first();
        //
        $this->validate($request, [
            'Transaction_channel_code' => 'required',
            'Transaction_channel_name' => 'required'
        ]);

        $transChannel = new LslCompanyTransactionChannelMaster();
        $transChannel->Company_id = $dataParams->Company_id;
        $transChannel->Create_user_id = $dataParams->Enrollment_id;
        $transChannel->Transaction_channel_code = $request->Transaction_channel_code;
        $transChannel->Transaction_channel_name = $request->Transaction_channel_name;
        $transChannel->save();

        return response()->json([
            'success' => true,
            'message' => 'Company Transaction Channel Was Saved Successfully!',
            'data'    => $transChannel
        ]);
    }

    public function saveSweepstake(Request $request)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_company_master.Company_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        //->where(['Enrollment_id' => $request->Enrollment_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->first();
        //
        $this->validate($request, [
            'Sweepstake_name' => 'required',
            'Winners'         => 'required',
            'Prize'           => 'required',
        ]);

        $sweepstake = new LslSweepstakeMaster();
        $sweepstake->Company_id = $dataParams->Company_id;
        $sweepstake->Create_user_id = $dataParams->Enrollment_id;
        $sweepstake->Sweepstake_name = $request->Sweepstake_name;
        $sweepstake->Winners = intval($request->Winners);
        $sweepstake->Prize = (int)$request->Prize;
        $sweepstake->Prize_description = $request->Prize_description;
        $sweepstake->Prize_image = $request->Prize_image;
        $sweepstake->Link_to_campaign = $request->Link_to_campaign;
        $sweepstake->From_date = $request->From_date;
        $sweepstake->To_date = $request->To_date;
        //$sweepstake->To_date = date('Y-m-d H:i:s'); //$request->Valid_from;
        $sweepstake->save();

        return response()->json([
            'success' => true,
            'message' => 'Sweepstake Was Saved Successfully!',
            'data'    => $sweepstake
        ]);

    }

    public function savePartnerCategory(Request $request)
    {
        $enrollmentId = DB::table('lsl_enrollment_master')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Phone_no')
                        ->where(['Membership_id' => $request->Membership_id])
                        ->first();
        //
        //dd($enrollmentId);
        $this->validate($request, [
            'Partner_category_name' => 'required'
        ]);

        $partnerCategory = new LslPartnerCategory();
        $partnerCategory->Create_User_id = $enrollmentId->Enrollment_id;
        $partnerCategory->Partner_category_name = $request->Partner_category_name;
        $partnerCategory->save();

        return response()->json([
            'success' => true,
            'message' => 'Partner Category Was Saved Successfully!',
            'data'    => $partnerCategory
        ]);
    }

    public function storePartner(Request $request)
    {
        $companyEnrollmentDetails = DB::table('lsl_enrollment_master')
                                      ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                                      ->join('lsl_partner_category', 'lsl_enrollment_master.Enrollment_id', 'lsl_partner_category.Create_User_id')
                                      ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_partner_category.Partner_category_id')
                                      ->where(['lsl_company_master.Company_id' => $request->Company_id])
                                      ->where(['lsl_partner_category.Partner_category_id' => $request->Partner_category_id])
                                      //->where(['Enrollment_id' => $request->Enrollment_id])
                                      ->where('lsl_enrollment_master.Membership_id', '=', $request->Membership_id)
                                      ->first();
        //
        $this->validate($request, [
            'Partner_name' => 'required',
            'Partner_address' => 'required'
        ]);

        $savePartner = new LslPartnerMaster();
        $savePartner->Company_id = $companyEnrollmentDetails->Company_id;
        $savePartner->Create_User_id = $companyEnrollmentDetails->Enrollment_id;
        $savePartner->Partner_category_id = $companyEnrollmentDetails->Partner_category_id;
        $savePartner->Partner_type = $request->Partner_type;
        $savePartner->Partner_name = $request->Partner_name;
        $savePartner->Partner_address = $request->Partner_address;
        $savePartner->Partner_contact_person_name = $request->Partner_contact_person_name;
        $savePartner->Partner_contact_person_email = $request->Partner_contact_person_email;
        $savePartner->Partner_logo = $request->Partner_logo;
        $savePartner->Corporate_email = $request->Corporate_email;
        $savePartner->save();

        return response()->json([
            'success' => true,
            'message' => 'Partner Was Saved Successfully!',
            'data' => $savePartner
        ]);
    }
}
