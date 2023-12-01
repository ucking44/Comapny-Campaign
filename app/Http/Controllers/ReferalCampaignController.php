<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;

class ReferalCampaignController extends Controller
{
    public function getReferalCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);
        //
        $referalCampaign = DB::table('lsl_campaign_master')
                             ->where('Transaction_amt_flag', '=', 0)->where('Upgrade_privilege', '=', null)
                             ->where('Spend_amt_flag', '=', 0)->where('Benefit_communication', '=', null)
                             ->where('Special_day', '=', NULL)->where('Recuring_campaign_flag', '=', 0)
                             ->where('Tier_id', '=', NULL)->where('Sweepstake_id', '=', null)
                             ->where('Reward_points', '!=', 0)->where('Tier_flag', '=', 0)->where('Sweepstake_flag', '=', 0)
                             ->get();
        //dd($referalCampaign);
        //
        return response()->json([
            'success' => true,
            'total'  => count($referalCampaign),
            'data'    => $referalCampaign
        ]);

    }

    public function saveReferalCampaign(Request $request)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        //->join('lsl_codedecode_master', 'lsl_company_master.Company_type', 'lsl_codedecode_master.Code_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_tier_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_master.Create_User_id')
                        //->join('lsl_partner_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_partner_master.Create_User_id')
                        //->join('lsl_branch_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_branch_master.Create_User_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        //->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        ->first();
        //
        //dd($dataParams);
        $this->validate($request, [
            'Campaign_name' => 'required',
            //'Sweepstake_ticket_limit' => 'required'
        ]);

        try
        {
            $referalCampaign = new LslCampaignMaster();
            //$referalCampaign->Code_id = $dataParams->Code_id;
            $referalCampaign->Company_id = $dataParams->Company_id;
            //$referalCampaign->branch_id = $dataParams->branch_id;
            $referalCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $referalCampaign->Create_User_id = $dataParams->Enrollment_id;
            //$referalCampaign->Tier_id = $dataParams->Tier_id;
            //$referalCampaign->Benefit_partner_id = $dataParams->Partner_id;
            //$referalCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $referalCampaign->Campaign_name = $request->Campaign_name;
            $referalCampaign->Campaign_description = $request->Campaign_description;
            $referalCampaign->Campaign_type = (int)$request->Campaign_type;
            $referalCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $referalCampaign->From_date = $request->From_date;
            $referalCampaign->To_date = $request->To_date;
            $referalCampaign->Tier_flag = (int)$request->Tier_flag;
            //$referalCampaign->Active_flag = $request->Active_flag;
            $referalCampaign->Reward_flag = (int)$request->Reward_flag;
            $referalCampaign->Reward_points = (int)$request->Reward_points;
            $referalCampaign->Reward_percent = (int)$request->Reward_percent;
            $referalCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $referalCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $referalCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $referalCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $referalCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $referalCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $referalCampaign->operator = $request->operator;
            $referalCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $referalCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $referalCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $referalCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $referalCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $referalCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $referalCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $referalCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $referalCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $referalCampaign->Special_day = $request->Special_day;
            $referalCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $referalCampaign->Schedule = (int)$request->Schedule;
            $referalCampaign->campaign_status = $request->campaign_status;
            $referalCampaign->Start_time = $request->Start_time;
            $referalCampaign->End_time = $request->End_time;
            $referalCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $referalCampaign->Discount = (int)$request->Discount;
            $referalCampaign->Discrete_amt = $request->Discrete_amt;
            $referalCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $referalCampaign->Spend_amount = (int)$request->Spend_amount;
            $referalCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $referalCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $referalCampaign->LBS_linked = (int)$request->LBS_linked;
            $referalCampaign->Benefit_description = $request->Benefit_description;
            $referalCampaign->Benefit_communication = $request->Benefit_communication;

            if($referalCampaign->From_date > $referalCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($referalCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            //dd($referalCampaign);
            $referalCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $referalCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            return response()->json([
                'success' => true,
                'message' => 'Referal Campaign Was Saved Successfully!',
                'data'     => $referalCampaign
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

    public function updateReferalCampaign(Request $request, $id)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        //->join('lsl_codedecode_master', 'lsl_company_master.Company_type', 'lsl_codedecode_master.Code_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_tier_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_master.Create_User_id')
                        //->join('lsl_partner_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_partner_master.Create_User_id')
                        //->join('lsl_branch_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_branch_master.Create_User_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        //->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        ->first();
        //
        try
        {
            if(LslCampaignMaster::where('Campaign_id', '=', $id)->exists())
            {
                $updateReferalCampaign = LslCampaignMaster::findOrFail($id);
                $updateReferalCampaign->Company_id = $dataParams->Company_id;
                //$updateReferalCampaign->branch_id = $dataParams->branch_id;
                $updateReferalCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateReferalCampaign->Create_User_id = $dataParams->Enrollment_id;
                //$updateReferalCampaign->Tier_id = $dataParams->Tier_id;
                //$updateReferalCampaign->Benefit_partner_id = $dataParams->Partner_id;
                //$updateReferalCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateReferalCampaign->Campaign_name = $request->Campaign_name;
                $updateReferalCampaign->Campaign_description = $request->Campaign_description;
                $updateReferalCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateReferalCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateReferalCampaign->From_date = $request->From_date;
                $updateReferalCampaign->To_date = $request->To_date;
                $updateReferalCampaign->Tier_flag = (int)$request->Tier_flag;
                //$updateReferalCampaign->Active_flag = $request->Active_flag;
                $updateReferalCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateReferalCampaign->Reward_points = (int)$request->Reward_points;
                $updateReferalCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateReferalCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateReferalCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateReferalCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateReferalCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateReferalCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateReferalCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateReferalCampaign->operator = $request->operator;
                $updateReferalCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateReferalCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateReferalCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateReferalCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateReferalCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateReferalCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateReferalCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateReferalCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateReferalCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateReferalCampaign->Special_day = $request->Special_day;
                $updateReferalCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateReferalCampaign->Schedule = (int)$request->Schedule;
                $updateReferalCampaign->campaign_status = $request->campaign_status;
                $updateReferalCampaign->Start_time = $request->Start_time;
                $updateReferalCampaign->End_time = $request->End_time;
                $updateReferalCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateReferalCampaign->Discount = (int)$request->Discount;
                $updateReferalCampaign->Discrete_amt = $request->Discrete_amt;
                $updateReferalCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateReferalCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateReferalCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateReferalCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateReferalCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateReferalCampaign->Benefit_description = $request->Benefit_description;
                $updateReferalCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateReferalCampaign->From_date > $updateReferalCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updateReferalCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updateReferalCampaign->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Referal Campaign Was Updated Successfully!',
                    'data'     => $updateReferalCampaign
                ]);
            }

            return response()->json([
                'message' => 'Referal Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
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

    public function deleteReferalCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id)->exists())
        {
            $deleteReferalCam = LslCampaignMaster::findOrFail($id);
            $deleteReferalCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Referal Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Referal Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
