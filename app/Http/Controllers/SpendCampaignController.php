<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;

class SpendCampaignController extends Controller
{
    public function getSpendCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);
        //

        $spendCampaign = DB::table('lsl_campaign_master')
                           ->where('Max_reward_budget', '=', 0)
                           //->where('Reward_fix_amt_flag', '=', 1)
                           ->where('Spend_amt_flag', '=', NULL)
                           ->where('Cumulative_amount', '=', 0)
                           ->where('Schedule', '=', null)
                           ->where('Recuring_campaign_flag', '=', null)
                           ->get();

        return response()->json([
            'success' => true,
            'total'  => count($spendCampaign),
            'data'    => $spendCampaign
        ]);
    }

    public function saveSpendCampaign(Request $request)
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
            $spendCampaign = new LslCampaignMaster();
            //$spendCampaign->Code_id = $dataParams->Code_id;
            $spendCampaign->Company_id = $dataParams->Company_id;
            $spendCampaign->Product_group_id = $dataParams->Product_group_id;
            $spendCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $spendCampaign->Transaction_id = $dataParams->Transaction_id;
            $spendCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $spendCampaign->branch_id = $dataParams->branch_id;
            $spendCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $spendCampaign->Create_User_id = $dataParams->Enrollment_id;
            $spendCampaign->Tier_id = $dataParams->Tier_id;
            //$spendCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $spendCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $spendCampaign->Campaign_name = $request->Campaign_name;
            $spendCampaign->Campaign_description = $request->Campaign_description;
            $spendCampaign->Campaign_type = (int)$request->Campaign_type;
            $spendCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $spendCampaign->From_date = $request->From_date;
            $spendCampaign->To_date = $request->To_date;
            $spendCampaign->Tier_flag = (int)$request->Tier_flag;
            $spendCampaign->Active_flag = (int)$request->Active_flag;
            $spendCampaign->Reward_flag = (int)$request->Reward_flag;
            $spendCampaign->Reward_points = (int)$request->Reward_points;
            $spendCampaign->Reward_percent = (int)$request->Reward_percent;
            $spendCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $spendCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $spendCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $spendCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $spendCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $spendCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $spendCampaign->operator = $request->operator;
            $spendCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $spendCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $spendCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $spendCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $spendCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $spendCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $spendCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $spendCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $spendCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $spendCampaign->Special_day = (int)$request->Special_day;
            $spendCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $spendCampaign->Schedule = (int)$request->Schedule;
            $spendCampaign->campaign_status = $request->campaign_status;
            $spendCampaign->Start_time = $request->Start_time;
            $spendCampaign->End_time = $request->End_time;
            $spendCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $spendCampaign->Discount = (int)$request->Discount;
            $spendCampaign->Discrete_amt = $request->Discrete_amt;
            $spendCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $spendCampaign->Spend_amount = (int)$request->Spend_amount;
            $spendCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $spendCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $spendCampaign->LBS_linked = (int)$request->LBS_linked;
            $spendCampaign->Benefit_description = $request->Benefit_description;
            $spendCampaign->Benefit_communication = $request->Benefit_communication;

            if($spendCampaign->From_date > $spendCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Greater Than To Date'
                ]);
            }

            if($spendCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Less Than Now'
                ]);
            }

            $spendCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $spendCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            return response()->json([
                'success' => true,
                'message' => 'Spend Campaign Was Saved Successfully!',
                'data'     => $spendCampaign
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

    public function updateSpendCampaign(Request $request, $id)
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
            if(LslCampaignMaster::where('Campaign_id', '=', $id)->exists())
            {
                $updateSpendCampaign = LslCampaignMaster::findOrFail($id);
                //$updateSpendCampaign->Code_id = $dataParams->Code_id;
                $updateSpendCampaign->Company_id = $dataParams->Company_id;
                $updateSpendCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateSpendCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateSpendCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateSpendCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateSpendCampaign->branch_id = $dataParams->branch_id;
                $updateSpendCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateSpendCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateSpendCampaign->Tier_id = $dataParams->Tier_id;
                //$updateSpendCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateSpendCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateSpendCampaign->Campaign_name = $request->Campaign_name;
                $updateSpendCampaign->Campaign_description = $request->Campaign_description;
                $updateSpendCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateSpendCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateSpendCampaign->From_date = $request->From_date;
                $updateSpendCampaign->To_date = $request->To_date;
                $updateSpendCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateSpendCampaign->Active_flag = (int)$request->Active_flag;
                $updateSpendCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateSpendCampaign->Reward_points = (int)$request->Reward_points;
                $updateSpendCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateSpendCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateSpendCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateSpendCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateSpendCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateSpendCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateSpendCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateSpendCampaign->operator = $request->operator;
                $updateSpendCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateSpendCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateSpendCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateSpendCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateSpendCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateSpendCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateSpendCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateSpendCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateSpendCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateSpendCampaign->Special_day = (int)$request->Special_day;
                $updateSpendCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateSpendCampaign->Schedule = (int)$request->Schedule;
                $updateSpendCampaign->campaign_status = $request->campaign_status;
                $updateSpendCampaign->Start_time = $request->Start_time;
                $updateSpendCampaign->End_time = $request->End_time;
                $updateSpendCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateSpendCampaign->Discount = (int)$request->Discount;
                $updateSpendCampaign->Discrete_amt = $request->Discrete_amt;
                $updateSpendCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateSpendCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateSpendCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateSpendCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateSpendCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateSpendCampaign->Benefit_description = $request->Benefit_description;
                $updateSpendCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateSpendCampaign->From_date > $updateSpendCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Greater Than To Date'
                    ]);
                }

                if($updateSpendCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Less Than Now'
                    ]);
                }

                $updateSpendCampaign->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Spend Campaign Was Updated Successfully!',
                    'data'     => $updateSpendCampaign
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

    public function deleteSpendCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deletespeCam = LslCampaignMaster::findOrFail($id);
            $deletespeCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Spend Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Spend Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);

    }

}
