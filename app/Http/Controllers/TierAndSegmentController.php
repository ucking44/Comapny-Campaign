<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LslTierMaster;
use App\Models\LslTierLevelMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslTierCriteriaMaster;

class TierAndSegmentController extends Controller
{
    public function getAllTierLevel()
    {
        $allTierLevel = LslTierLevelMaster::get();

        return response()->json([
            'success' => true,
            'data' => $allTierLevel
        ]);
    }

    public function getAllTierCriteria()
    {
        $allTierCriteria = LslTierCriteriaMaster::get();

        return response()->json([
            'success' => true,
            'data' => $allTierCriteria
        ]);
    }

    public function getAllTier()
    {
        $allTier = LslTierMaster::get();

        return response()->json([
            'success' => true,
            'data' => $allTier
        ]);
    }

    public function getAllSegmentType()
    {
        $allSegmentType = LslSegmentTypeMaster::get();

        return response()->json([
            'success' => true,
            'data' => $allSegmentType
        ]);
    }

    public function getAllSegment()
    {
        $allSegment = LslSegmentMaster::get();

        return response()->json([
            'success' => true,
            'data' => $allSegment
        ]);
    }

    public function saveTierLevel(Request $request)
    {
        $enrollment = DB::table('lsl_enrollment_master')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Phone_no')
                        ->where(['Enrollment_id' => $request->Enrollment_id])
                        ->first();
        //
        $this->validate($request, [
            "Tier_level" => "required"
        ]);

        $tierLevel = new LslTierLevelMaster();
        $tierLevel->Create_User_id = $enrollment->Enrollment_id;
        $tierLevel->Tier_level = $request->Tier_level;
        $tierLevel->save();

        return response()->json([
            'success' => true,
            'message' => 'Tier Level Was Successfully Saved!',
            'data' => $tierLevel
        ]);
    }

    public function saveTierCriteria(Request $request)
    {
        $enrollmentId = DB::table('lsl_enrollment_master')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Phone_no')
                        ->where(['Enrollment_id' => $request->Enrollment_id])
                        ->first();
        //
        $this->validate($request, [
            "Tier_criteria_name" => "required",
            "Criteria_description" => "required"
        ]);

        $tierCriteria = new LslTierCriteriaMaster();
        $tierCriteria->Create_User_id = $enrollmentId->Enrollment_id;
        $tierCriteria->Tier_criteria_name = $request->Tier_criteria_name;
        $tierCriteria->Criteria_description = $request->Criteria_description;
        $tierCriteria->save();

        return response()->json([
            'success' => true,
            'message' => 'Tier Criteria Was Successfully Saved!',
            'data' => $tierCriteria
        ]);
    }

    public function saveTier(Request $request)
    {
        $details = DB::table('lsl_company_master')
                        ->join('lsl_enrollment_master', 'lsl_company_master.Company_id', 'lsl_enrollment_master.Company_id')
                        ->join('lsl_tier_level_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_level_master.Create_User_id')
                        ->join('lsl_tier_criteria_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_criteria_master.Create_User_id')
                        ->select('lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Company_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Phone_no', 'lsl_tier_level_master.Tier_level', 'lsl_tier_level_master.Tier_level_id', 'lsl_tier_criteria_master.Tier_criteria_id', 'lsl_tier_criteria_master.Tier_criteria_name')
                        //->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['Enrollment_id' => $request->Enrollment_id])
                        ->where(['Tier_level_id' => $request->Tier_level_id])
                        ->where(['Tier_criteria_id' => $request->Upgrade_criteria])
                        ->first();
        //
        $this->validate($request, [
            "Tier_name" => "required",
            "Tier_period" => "required",
            "Upgrade_criteria_value" => "required",
            "Redeemption_level" => "required",
            "minimum_points_balance" => "required",
            "minimum_points_balance_redeem" => "required",
            "Tier_invitation" => "required"
        ]);

        $tier = new LslTierMaster();
        $tier->Company_id = $details->Company_id;
        $tier->Create_User_id = $details->Enrollment_id;
        $tier->Tier_level_id = $details->Tier_level_id;
        $tier->Upgrade_criteria = $details->Tier_criteria_id;
        $tier->Tier_name = $request->Tier_name;
        $tier->Tier_period = (int)$request->Tier_period;
        $tier->Upgrade_criteria_value = (int)$request->Upgrade_criteria_value;
        $tier->Redeemption_level = $request->Redeemption_level;
        $tier->minimum_points_balance = (int)$request->minimum_points_balance;
        $tier->minimum_points_balance_redeem = (int)$request->minimum_points_balance_redeem;
        $tier->Tier_invitation = $request->Tier_invitation;
        $tier->save();

        return response()->json([
            'success' => true,
            'message' => 'Tier Was Successfully Saved!',
            'data' => $tier
        ]);
    }

    public function saveSegmentType(Request $request)
    {
        $typeDetails = DB::table('lsl_company_master')
                        ->join('lsl_enrollment_master', 'lsl_company_master.Company_id', 'lsl_enrollment_master.Company_id')
                        ->select('lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Company_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Phone_no')
                        //->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['Enrollment_id' => $request->Enrollment_id])
                        ->first();
        //
        $this->validate($request, [
            "Segment_type_name" => "required",
            "Description" => "required"
        ]);

        $segmentType = new LslSegmentTypeMaster();
        $segmentType->Company_id = $typeDetails->Company_id;
        $segmentType->Create_User_id = $typeDetails->Enrollment_id;
        $segmentType->Segment_type_name = $request->Segment_type_name;
        $segmentType->Description = $request->Description;
        $segmentType->save();

        return response()->json([
            'success' => true,
            'message' => 'Sement Type Was Successfully Saved!',
            'data' => $segmentType
        ]);
    }

    public function saveSegment(Request $request)
    {
        $companyInfo = DB::table('lsl_company_master')
                         ->join('lsl_enrollment_master', 'lsl_company_master.Company_id', 'lsl_enrollment_master.Company_id')
                         ->join('lsl_segment_type_master', 'lsl_company_master.Company_id', 'lsl_segment_type_master.Company_id')
                         ->select('lsl_company_master.Company_id', 'lsl_company_master.Company_name', 'lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Company_id', 'lsl_enrollment_master.First_name', 'lsl_enrollment_master.Phone_no', 'lsl_segment_type_master.Segment_type_name')
                        //->where(['lsl_company_master.Company_id' => $request->Company_id])
                         ->where(['Enrollment_id' => $request->Enrollment_id])
                         ->where(['Segment_type_id' => $request->Segment_type_id])
                         ->first();
        //
        $this->validate($request, [
            "Segment_name" => "required",
            "Operator" => "required",
            "Value_from" => "required",
            "Value_to" => "required"
        ]);

        $segment = new LslSegmentMaster();
        $segment->Company_id = $companyInfo->Company_id;
        $segment->Create_User_id = $companyInfo->Enrollment_id;
        $segment->Segment_name = $request->Segment_name;
        $segment->Operator = $request->Operator;
        $segment->Value_from = (int)$request->Value_from;
        $segment->Value_to = (int)$request->Value_to;
        $segment->save();

        return response()->json([
            'success' => true,
            'message' => 'Sement Was Successfully Saved!',
            'data' => $segment
        ]);
    }

}
