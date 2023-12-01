<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;

class CashBackCampaignController extends Controller
{
    public function getCashBackCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //

        $cashBackCampaign = LslCampaignMaster::where('Max_reward_budget', '=', 0)
                                             ->where('Reward_fix_amt_flag', '=', 0)
                                             ->where('Cumulative_amount', '=', 0)
                                             ->where('Schedule', '=', 0)
                                             ->where('Recuring_campaign_flag', '=', 0)
                                             ->where('Fixed_amount', '=', 0)
                                             ->where('Reward_percent', '!=', 0)
                                             ->where('Spend_amt_flag', '=', 0)
                                             ->where('Transaction_amt_flag', '=', 1)
                                             ->where('Transaction_amount', '!=', 0)
                                             ->get();
        //
        return response()->json([
            'success' => true,
            'total'  => count($cashBackCampaign),
            'data'    => $cashBackCampaign
        ]);
    }

    public function saveCashBackCampaign(Request $request)
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
        $this->validate($request, [
            'Campaign_name' => 'required',
            //'Sweepstake_ticket_limit' => 'required'
        ]);

        try
        {
            $cashBackCampaign = new LslCampaignMaster();
            //$cashBackCampaign->Code_id = $dataParams->Code_id; Cashback_percent
            $cashBackCampaign->Company_id = $dataParams->Company_id;
            $cashBackCampaign->Product_group_id = $dataParams->Product_group_id;
            $cashBackCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $cashBackCampaign->Transaction_id = $dataParams->Transaction_id;
            $cashBackCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $cashBackCampaign->branch_id = $dataParams->branch_id;
            $cashBackCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $cashBackCampaign->Create_User_id = $dataParams->Enrollment_id;
            $cashBackCampaign->Tier_id = $dataParams->Tier_id;
            //$cashBackCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $cashBackCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $cashBackCampaign->Campaign_name = $request->Campaign_name;
            $cashBackCampaign->Campaign_description = $request->Campaign_description;
            $cashBackCampaign->Campaign_type = (int)$request->Campaign_type;
            $cashBackCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $cashBackCampaign->From_date = $request->From_date;
            $cashBackCampaign->To_date = $request->To_date;
            $cashBackCampaign->Tier_flag = (int)$request->Tier_flag;
            $cashBackCampaign->Active_flag = (int)$request->Active_flag;
            $cashBackCampaign->Reward_flag = (int)$request->Reward_flag;
            $cashBackCampaign->Reward_points = (int)$request->Reward_points;
            $cashBackCampaign->Reward_percent = (int)$request->Reward_percent;
            $cashBackCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $cashBackCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $cashBackCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $cashBackCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $cashBackCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $cashBackCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $cashBackCampaign->operator = $request->operator;
            $cashBackCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $cashBackCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $cashBackCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $cashBackCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $cashBackCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $cashBackCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $cashBackCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $cashBackCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $cashBackCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $cashBackCampaign->Special_day = (int)$request->Special_day;
            $cashBackCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $cashBackCampaign->Schedule = (int)$request->Schedule;
            $cashBackCampaign->campaign_status = $request->campaign_status;
            $cashBackCampaign->Start_time = $request->Start_time;
            $cashBackCampaign->End_time = $request->End_time;
            $cashBackCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $cashBackCampaign->Discount = (int)$request->Discount;
            $cashBackCampaign->Discrete_amt = $request->Discrete_amt;
            $cashBackCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $cashBackCampaign->Spend_amount = (int)$request->Spend_amount;
            $cashBackCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $cashBackCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $cashBackCampaign->LBS_linked = (int)$request->LBS_linked;
            $cashBackCampaign->Benefit_description = $request->Benefit_description;
            $cashBackCampaign->Benefit_communication = $request->Benefit_communication;

            if($cashBackCampaign->From_date > $cashBackCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Greater Than To Date'
                ]);
            }

            if($cashBackCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Less Than Now'
                ]);
            }

            $cashBackCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $cashBackCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //
            return response()->json([
                'success' => true,
                'message' => 'Cash Back Campaign Was Saved Successfully!',
                'data'     => $cashBackCampaign
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

    public function updateCashBackCampaign(Request $request, $id)
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
            if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
            {
                $updateCashBackCampaign = LslCampaignMaster::findOrFail($id);
                //$updateCashBackCampaign->Code_id = $dataParams->Code_id;
                $updateCashBackCampaign->Company_id = $dataParams->Company_id;
                $updateCashBackCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateCashBackCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateCashBackCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateCashBackCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateCashBackCampaign->branch_id = $dataParams->branch_id;
                $updateCashBackCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateCashBackCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateCashBackCampaign->Tier_id = $dataParams->Tier_id;
                //$updateCashBackCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateCashBackCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateCashBackCampaign->Campaign_name = $request->Campaign_name;
                $updateCashBackCampaign->Campaign_description = $request->Campaign_description;
                $updateCashBackCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateCashBackCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateCashBackCampaign->From_date = $request->From_date;
                $updateCashBackCampaign->To_date = $request->To_date;
                $updateCashBackCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateCashBackCampaign->Active_flag = (int)$request->Active_flag;
                $updateCashBackCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateCashBackCampaign->Reward_points = (int)$request->Reward_points;
                $updateCashBackCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateCashBackCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateCashBackCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateCashBackCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateCashBackCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateCashBackCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateCashBackCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateCashBackCampaign->operator = $request->operator;
                $updateCashBackCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateCashBackCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateCashBackCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateCashBackCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateCashBackCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateCashBackCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateCashBackCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateCashBackCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateCashBackCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateCashBackCampaign->Special_day = (int)$request->Special_day;
                $updateCashBackCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateCashBackCampaign->Schedule = (int)$request->Schedule;
                $updateCashBackCampaign->campaign_status = $request->campaign_status;
                $updateCashBackCampaign->Start_time = $request->Start_time;
                $updateCashBackCampaign->End_time = $request->End_time;
                $updateCashBackCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateCashBackCampaign->Discount = (int)$request->Discount;
                $updateCashBackCampaign->Discrete_amt = $request->Discrete_amt;
                $updateCashBackCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateCashBackCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateCashBackCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateCashBackCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateCashBackCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateCashBackCampaign->Benefit_description = $request->Benefit_description;
                $updateCashBackCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateCashBackCampaign->From_date > $updateCashBackCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Greater Than To Date'
                    ]);
                }

                if($updateCashBackCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Less Than Now'
                    ]);
                }

                $updateCashBackCampaign->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Cash Back Campaign Was Updated Successfully!',
                    'data'     => $updateCashBackCampaign
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

    public function deleteCashBackCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteCashBackCam = LslCampaignMaster::findOrFail($id);
            $deleteCashBackCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cash Back Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Cash Back Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);

    }

}
