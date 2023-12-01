<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;

class FixedBudgetCampaignController extends Controller
{
    public function getFixedBudgetCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //
        $fixedBudgetCampaign = LslCampaignMaster::where('Max_reward_budget', '!=', 0)
                                                ->where('Reward_fix_amt_flag', '=', 1)
                                                ->where('Spend_amt_flag', '=', NULL)
                                                ->where('Cumulative_amount', '=', 0)
                                                ->where('Schedule', '=', null)
                                                ->where('Recuring_campaign_flag', '=', null)
                                                ->get();
        //
        return response()->json([
            'success' => true,
            'total'  => count($fixedBudgetCampaign),
            'data'    => $fixedBudgetCampaign
        ]);
    }

    public function saveFixedBudgetCampaign(Request $request)
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
                        ->where(['lsl_product_group_master.Product_group_id' => $request->Product_group_id])
                        ->where(['lsl_product_brand_master.Product_brand_id' => $request->Product_brand_id])
                        ->where(['lsl_transaction_type_master.Transaction_id' => $request->Transaction_id])
                        ->where(['lsl_company_transaction_channel_master.Transaction_channel_id' => $request->Transaction_channel_id])
                        ->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        ->where(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
                        ->where(['lsl_tier_master.Tier_id' => $request->Tier_id])
                        //->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        ->first();
        //
        //dd($dataParams);
        $this->validate($request, [
            'Campaign_name' => 'required',
            //'Sweepstake_ticket_limit' => 'required'
        ]);

        try
        {
            $fixedBudgetCampaign = new LslCampaignMaster();
            //$fixedBudgetCampaign->Code_id = $dataParams->Code_id;
            $fixedBudgetCampaign->Company_id = $dataParams->Company_id;
            $fixedBudgetCampaign->Product_group_id = $dataParams->Product_group_id;
            $fixedBudgetCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $fixedBudgetCampaign->Transaction_id = $dataParams->Transaction_id;
            $fixedBudgetCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $fixedBudgetCampaign->branch_id = $dataParams->branch_id;
            $fixedBudgetCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $fixedBudgetCampaign->Create_User_id = $dataParams->Enrollment_id;
            $fixedBudgetCampaign->Tier_id = $dataParams->Tier_id;
            //$fixedBudgetCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $fixedBudgetCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $fixedBudgetCampaign->Campaign_name = $request->Campaign_name;
            $fixedBudgetCampaign->Campaign_description = $request->Campaign_description;
            $fixedBudgetCampaign->Campaign_type = (int)$request->Campaign_type;
            $fixedBudgetCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $fixedBudgetCampaign->From_date = $request->From_date;
            $fixedBudgetCampaign->To_date = $request->To_date;
            $fixedBudgetCampaign->Tier_flag = (int)$request->Tier_flag;
            $fixedBudgetCampaign->Active_flag = (int)$request->Active_flag;
            $fixedBudgetCampaign->Reward_flag = (int)$request->Reward_flag;
            $fixedBudgetCampaign->Reward_points = (int)$request->Reward_points;
            $fixedBudgetCampaign->Reward_percent = (int)$request->Reward_percent;
            $fixedBudgetCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $fixedBudgetCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $fixedBudgetCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $fixedBudgetCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $fixedBudgetCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $fixedBudgetCampaign->operator = $request->operator;
            $fixedBudgetCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $fixedBudgetCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $fixedBudgetCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $fixedBudgetCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $fixedBudgetCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $fixedBudgetCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $fixedBudgetCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $fixedBudgetCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $fixedBudgetCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $fixedBudgetCampaign->Special_day = (int)$request->Special_day;
            $fixedBudgetCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $fixedBudgetCampaign->Schedule = (int)$request->Schedule;
            $fixedBudgetCampaign->campaign_status = $request->campaign_status;
            $fixedBudgetCampaign->Start_time = $request->Start_time;
            $fixedBudgetCampaign->End_time = $request->End_time;
            $fixedBudgetCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $fixedBudgetCampaign->Discount = (int)$request->Discount;
            $fixedBudgetCampaign->Discrete_amt = $request->Discrete_amt;
            $fixedBudgetCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $fixedBudgetCampaign->Spend_amount = (int)$request->Spend_amount;
            $fixedBudgetCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $fixedBudgetCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $fixedBudgetCampaign->LBS_linked = (int)$request->LBS_linked;
            $fixedBudgetCampaign->Benefit_description = $request->Benefit_description;
            $fixedBudgetCampaign->Benefit_communication = $request->Benefit_communication;

            if($fixedBudgetCampaign->From_date > $fixedBudgetCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Greater Than To Date'
                ]);
            }

            if($fixedBudgetCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Less Than Now'
                ]);
            }

            $fixedBudgetCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $fixedBudgetCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            return response()->json([
                'success' => true,
                'message' => 'Fixed Budget Campaign Was Saved Successfully!',
                'data'     => $fixedBudgetCampaign
            ]);
        }
        catch (Exception $e)
        {
            return response([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);

        }

    }

    public function updateFixedBudgetCampaign(Request $request, $id)
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
                        ->where(['lsl_product_group_master.Product_group_id' => $request->Product_group_id])
                        ->where(['lsl_product_brand_master.Product_brand_id' => $request->Product_brand_id])
                        ->where(['lsl_transaction_type_master.Transaction_id' => $request->Transaction_id])
                        ->where(['lsl_company_transaction_channel_master.Transaction_channel_id' => $request->Transaction_channel_id])
                        ->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        ->where(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
                        ->where(['lsl_tier_master.Tier_id' => $request->Tier_id])
                        //->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        ->first();
        //
        try
        {
            if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
            {
                $updateFixedBudgetCampaign = LslCampaignMaster::findOrFail($id);
                //$updateFixedBudgetCampaign->Code_id = $dataParams->Code_id;
                $updateFixedBudgetCampaign->Company_id = $dataParams->Company_id;
                $updateFixedBudgetCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateFixedBudgetCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateFixedBudgetCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateFixedBudgetCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateFixedBudgetCampaign->branch_id = $dataParams->branch_id;
                $updateFixedBudgetCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateFixedBudgetCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateFixedBudgetCampaign->Tier_id = $dataParams->Tier_id;
                //$updateFixedBudgetCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateFixedBudgetCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateFixedBudgetCampaign->Campaign_name = $request->Campaign_name;
                $updateFixedBudgetCampaign->Campaign_description = $request->Campaign_description;
                $updateFixedBudgetCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateFixedBudgetCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateFixedBudgetCampaign->From_date = $request->From_date;
                $updateFixedBudgetCampaign->To_date = $request->To_date;
                $updateFixedBudgetCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateFixedBudgetCampaign->Active_flag = (int)$request->Active_flag;
                $updateFixedBudgetCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateFixedBudgetCampaign->Reward_points = (int)$request->Reward_points;
                $updateFixedBudgetCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateFixedBudgetCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateFixedBudgetCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateFixedBudgetCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateFixedBudgetCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateFixedBudgetCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateFixedBudgetCampaign->operator = $request->operator;
                $updateFixedBudgetCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateFixedBudgetCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateFixedBudgetCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateFixedBudgetCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateFixedBudgetCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateFixedBudgetCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateFixedBudgetCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateFixedBudgetCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateFixedBudgetCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateFixedBudgetCampaign->Special_day = (int)$request->Special_day;
                $updateFixedBudgetCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateFixedBudgetCampaign->Schedule = (int)$request->Schedule;
                $updateFixedBudgetCampaign->campaign_status = $request->campaign_status;
                $updateFixedBudgetCampaign->Start_time = $request->Start_time;
                $updateFixedBudgetCampaign->End_time = $request->End_time;
                $updateFixedBudgetCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateFixedBudgetCampaign->Discount = (int)$request->Discount;
                $updateFixedBudgetCampaign->Discrete_amt = $request->Discrete_amt;
                $updateFixedBudgetCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateFixedBudgetCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateFixedBudgetCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateFixedBudgetCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateFixedBudgetCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateFixedBudgetCampaign->Benefit_description = $request->Benefit_description;
                $updateFixedBudgetCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateFixedBudgetCampaign->From_date > $updateFixedBudgetCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Greater Than To Date'
                    ]);
                }

                if($updateFixedBudgetCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Less Than Now'
                    ]);
                }

                $updateFixedBudgetCampaign->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Fixed Budget Campaign Was Updated Successfully!',
                    'data'     => $updateFixedBudgetCampaign
                ]);

            }

            return response()->json([
                'message' => 'Game Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
            ]);
        }
        catch (Exception $e)
        {
            return response([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);

        }

    }

    public function deleteFixedBudgetCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteFixBudgCam = LslCampaignMaster::findOrFail($id);
            $deleteFixBudgCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fixed Budget Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Fixed Budget Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);

    }
}
