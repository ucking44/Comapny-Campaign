<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class SpecialDateController extends Controller
{
    public function getSpecialDateCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //

        $specialDateCampaign = LslCampaignMaster::where('Transaction_amt_flag', '=', 1)->where('Transaction_amount', '!=', null)
                                                ->where('Recuring_campaign_flag', '=', 1)->where('Special_day', '!=', 0)
                                                ->where('Benefit_communication', '=', NULL)->where('Upgrade_privilege', '=', null)
                                                ->where('Special_occasian_criteria', '!=', null)
                                                ->get();
        //
        return response()->json([
            'success' => true,
            'total'  => count($specialDateCampaign),
            'data'    => $specialDateCampaign
        ]);

    }

    public function saveSpecialDateCampaign(Request $request)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        //->join('lsl_codedecode_master', 'lsl_company_master.Company_type', 'lsl_codedecode_master.Code_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_tier_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_master.Create_User_id')
                        //->join('lsl_partner_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_partner_master.Create_User_id')
                        ->join('lsl_product_group_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_product_group_master.Create_User_id')
                        ->join('lsl_product_brand_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_product_brand_master.Create_User_id')
                        ->join('lsl_transaction_type_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_transaction_type_master.Create_User_id')
                        ->join('lsl_company_transaction_channel_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_company_transaction_channel_master.Create_User_id')
                        ->join('lsl_branch_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_branch_master.Create_User_id')
                        ->join('lsl_sweepstake_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_sweepstake_master.Create_user_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_product_group_master.Product_group_id', 'lsl_product_brand_master.Product_brand_id', 'lsl_transaction_type_master.Transaction_id', 'lsl_company_transaction_channel_master.Transaction_channel_id', 'lsl_branch_master.branch_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id', 'lsl_tier_master.Tier_id', 'lsl_sweepstake_master.Sweepstake_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        //->where(['Enrollment_id' => $request->Enrollment_id])
                        //->where(['lsl_codedecode_master.Code_id' => $request->Code_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->where(['lsl_tier_master.Tier_id' => $request->Tier_id])
                        ->where(['lsl_product_group_master.Product_group_id' => $request->Product_group_id])
                        ->where(['lsl_product_brand_master.Product_brand_id' => $request->Product_brand_id])
                        ->where(['lsl_transaction_type_master.Transaction_id' => $request->Transaction_id])
                        ->where(['lsl_company_transaction_channel_master.Transaction_channel_id' => $request->Transaction_channel_id])
                        ->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        //->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        ->where(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
                        ->first();
        //
        //dd($dataParams);
        $this->validate($request, [
            'Campaign_name' => 'required',
            //'Sweepstake_ticket_limit' => 'required'
        ]);

        try
        {
            DB::beginTransaction();
            
            $specialDateCampaign = new LslCampaignMaster();
            //$specialDateCampaign->Code_id = $dataParams->Code_id;
            $specialDateCampaign->Company_id = $dataParams->Company_id;
            $specialDateCampaign->Product_group_id = $dataParams->Product_group_id;
            $specialDateCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $specialDateCampaign->Transaction_id = $dataParams->Transaction_id;
            $specialDateCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $specialDateCampaign->branch_id = $dataParams->branch_id;
            $specialDateCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $specialDateCampaign->Create_User_id = $dataParams->Enrollment_id;
            $specialDateCampaign->Tier_id = $dataParams->Tier_id;
            //$specialDateCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $specialDateCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $specialDateCampaign->Campaign_name = $request->Campaign_name;
            $specialDateCampaign->Campaign_description = $request->Campaign_description;
            $specialDateCampaign->Campaign_type = (int)$request->Campaign_type;
            $specialDateCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $specialDateCampaign->From_date = $request->From_date;
            $specialDateCampaign->To_date = $request->To_date;
            $specialDateCampaign->Tier_flag = (int)$request->Tier_flag;
            $specialDateCampaign->Active_flag = (int)$request->Active_flag;
            $specialDateCampaign->Reward_flag = (int)$request->Reward_flag;
            $specialDateCampaign->Reward_points = (int)$request->Reward_points;
            $specialDateCampaign->Reward_percent = (int)$request->Reward_percent;
            $specialDateCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $specialDateCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $specialDateCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $specialDateCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $specialDateCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $specialDateCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $specialDateCampaign->operator = $request->operator;
            $specialDateCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $specialDateCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $specialDateCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $specialDateCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $specialDateCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $specialDateCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $specialDateCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $specialDateCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $specialDateCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $specialDateCampaign->Special_day = (int)$request->Special_day;
            $specialDateCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $specialDateCampaign->Schedule = (int)$request->Schedule;
            $specialDateCampaign->campaign_status = $request->campaign_status;
            $specialDateCampaign->Start_time = $request->Start_time;
            $specialDateCampaign->End_time = $request->End_time;
            $specialDateCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $specialDateCampaign->Discount = (int)$request->Discount;
            $specialDateCampaign->Discrete_amt = $request->Discrete_amt;
            $specialDateCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $specialDateCampaign->Spend_amount = (int)$request->Spend_amount;
            $specialDateCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $specialDateCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $specialDateCampaign->LBS_linked = (int)$request->LBS_linked;
            $specialDateCampaign->Benefit_description = $request->Benefit_description;
            $specialDateCampaign->Benefit_communication = $request->Benefit_communication;

            if($specialDateCampaign->From_date > $specialDateCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Greater Than To Date'
                ]);
            }

            if($specialDateCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Less Than Now'
                ]);
            }

            ///dd($specialDateCampaign);
            $specialDateCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $specialDateCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $specialDateCampaign->Campaign_id;
            $saveCampaignSchedule->Jan = (int)$request->Jan;
            $saveCampaignSchedule->Feb = (int)$request->Feb;
            $saveCampaignSchedule->Mar = (int)$request->Mar;
            $saveCampaignSchedule->Apr = (int)$request->Apr;
            $saveCampaignSchedule->May = (int)$request->May;
            $saveCampaignSchedule->Jun = (int)$request->Jan;
            $saveCampaignSchedule->Jul = (int)$request->Jul;
            $saveCampaignSchedule->Aug = (int)$request->Aug;
            $saveCampaignSchedule->Sep = (int)$request->Sep;
            $saveCampaignSchedule->Oct = (int)$request->Oct;
            $saveCampaignSchedule->Nov = (int)$request->Nov;
            $saveCampaignSchedule->Dec = (int)$request->Dec;
            $saveCampaignSchedule->Mon = (int)$request->Mon;
            $saveCampaignSchedule->Tue = (int)$request->Tue;
            $saveCampaignSchedule->Wed = (int)$request->Wed;
            $saveCampaignSchedule->Thu = (int)$request->Thu;
            $saveCampaignSchedule->Fri = (int)$request->Fri;
            $saveCampaignSchedule->Sat = (int)$request->Sat;
            $saveCampaignSchedule->Sun = (int)$request->Sun;
            $saveCampaignSchedule->First_week = (int)$request->First_week;
            $saveCampaignSchedule->Second_week = (int)$request->Second_week;
            $saveCampaignSchedule->Third_week = (int)$request->Third_week;
            $saveCampaignSchedule->Fourth_week = (int)$request->Fourth_week;
            $saveCampaignSchedule->Start_time = $request->Start_time;
            $saveCampaignSchedule->End_time = $request->End_time;
            $saveCampaignSchedule->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Special Date Periodic Campaign Was Saved Successfully!',
                'data'     => $specialDateCampaign
            ]);
        }
        catch (Exception $e)
        {
            DB::rollback();

            return response([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }

    }

    public function updateSpecialDateCampaign(Request $request, $id)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_tier_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_master.Create_User_id')
                        //->join('lsl_partner_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_partner_master.Create_User_id')
                        ->join('lsl_product_group_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_product_group_master.Create_User_id')
                        ->join('lsl_product_brand_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_product_brand_master.Create_User_id')
                        ->join('lsl_transaction_type_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_transaction_type_master.Create_User_id')
                        ->join('lsl_company_transaction_channel_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_company_transaction_channel_master.Create_User_id')
                        ->join('lsl_branch_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_branch_master.Create_User_id')
                        ->join('lsl_sweepstake_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_sweepstake_master.Create_user_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_product_group_master.Product_group_id', 'lsl_product_brand_master.Product_brand_id', 'lsl_transaction_type_master.Transaction_id', 'lsl_company_transaction_channel_master.Transaction_channel_id', 'lsl_branch_master.branch_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id', 'lsl_tier_master.Tier_id', 'lsl_sweepstake_master.Sweepstake_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->where(['lsl_tier_master.Tier_id' => $request->Tier_id])
                        ->where(['lsl_product_group_master.Product_group_id' => $request->Product_group_id])
                        ->where(['lsl_product_brand_master.Product_brand_id' => $request->Product_brand_id])
                        ->where(['lsl_transaction_type_master.Transaction_id' => $request->Transaction_id])
                        ->where(['lsl_company_transaction_channel_master.Transaction_channel_id' => $request->Transaction_channel_id])
                        ->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        //->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        ->where(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
                        ->first();
        //
        try
        {
            DB::beginTransaction();

            if(LslCampaignMaster::where('Campaign_id', '=', $id)->exists())
            {
                $updateSpecialDateCampaign = LslCampaignMaster::findOrFail($id);
                //$updateSpecialDateCampaign->Code_id = $dataParams->Code_id;
                $updateSpecialDateCampaign->Company_id = $dataParams->Company_id;
                $updateSpecialDateCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateSpecialDateCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateSpecialDateCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateSpecialDateCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateSpecialDateCampaign->branch_id = $dataParams->branch_id;
                $updateSpecialDateCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateSpecialDateCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateSpecialDateCampaign->Tier_id = $dataParams->Tier_id;
                //$updateSpecialDateCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateSpecialDateCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateSpecialDateCampaign->Campaign_name = $request->Campaign_name;
                $updateSpecialDateCampaign->Campaign_description = $request->Campaign_description;
                $updateSpecialDateCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateSpecialDateCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateSpecialDateCampaign->From_date = $request->From_date;
                $updateSpecialDateCampaign->To_date = $request->To_date;
                $updateSpecialDateCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateSpecialDateCampaign->Active_flag = (int)$request->Active_flag;
                $updateSpecialDateCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateSpecialDateCampaign->Reward_points = (int)$request->Reward_points;
                $updateSpecialDateCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateSpecialDateCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateSpecialDateCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateSpecialDateCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateSpecialDateCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateSpecialDateCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateSpecialDateCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateSpecialDateCampaign->operator = $request->operator;
                $updateSpecialDateCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateSpecialDateCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateSpecialDateCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateSpecialDateCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateSpecialDateCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateSpecialDateCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateSpecialDateCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateSpecialDateCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateSpecialDateCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateSpecialDateCampaign->Special_day = (int)$request->Special_day;
                $updateSpecialDateCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateSpecialDateCampaign->Schedule = (int)$request->Schedule;
                $updateSpecialDateCampaign->campaign_status = $request->campaign_status;
                $updateSpecialDateCampaign->Start_time = $request->Start_time;
                $updateSpecialDateCampaign->End_time = $request->End_time;
                $updateSpecialDateCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateSpecialDateCampaign->Discount = (int)$request->Discount;
                $updateSpecialDateCampaign->Discrete_amt = $request->Discrete_amt;
                $updateSpecialDateCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateSpecialDateCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateSpecialDateCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateSpecialDateCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateSpecialDateCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateSpecialDateCampaign->Benefit_description = $request->Benefit_description;
                $updateSpecialDateCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateSpecialDateCampaign->From_date > $updateSpecialDateCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Greater Than To Date'
                    ]);
                }

                if($updateSpecialDateCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Less Than Now'
                    ]);
                }

                $updateSpecialDateCampaign->save();

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateSpecialDateCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Special Date Periodic Campaign Was Updated Successfully!',
                    'data'     => $updateSpecialDateCampaign
                ]);

            }

            return response()->json([
                'message' => 'Game Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
            ]);

        }
        catch (Exception $e)
        {
            DB::rollback();

            return response([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);

        }

    }

    public function deleteSpecialDateCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteSpecialDateCam = LslCampaignMaster::findOrFail($id);
            $deleteSpecialDateCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Special Date Periodic Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Special Date Periodic Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }

}
