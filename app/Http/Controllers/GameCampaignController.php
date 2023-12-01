<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;

class GameCampaignController extends Controller
{
    public function getGameCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //

        $gameCampaign = DB::table('lsl_campaign_master')
                                   ->where('Transaction_amt_flag', '=', 0)->where('Game_id', '!=', null)
                                   ->where('Reward_once_flag', '=', 1)->where('Reward_flag', '=', 0)
                                   ->where('Special_day', '=', 0)->where('Recuring_campaign_flag', '=', 0)
                                   ->where('Reward_points', '!=', 0)->where('Max_reward_budget', '=', 0)
                                   ->where('Discrete_amt', '=', null)
                                   ->get();

        return response()->json([
            'success' => true,
            'total'  => count($gameCampaign),
            'data'    => $gameCampaign
        ]);

    }

    public function saveGameCampaign(Request $request)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_game_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_game_master.Create_User_id')
                        ->join('lsl_game_company_configuration', 'lsl_enrollment_master.Enrollment_id', 'lsl_game_company_configuration.Create_User_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_game_master.Game_id', 'lsl_game_company_configuration.Game_configuration_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->where(['lsl_game_master.Game_id' => $request->Game_id])
                        ->where(['lsl_game_company_configuration.Game_configuration_id' => $request->Game_configuration_id])
                        ->first();
        //
        //dd($dataParams);
        $this->validate($request, [
            'Campaign_name' => 'required'
        ]);

        try
        {
            $gameCampaign = new LslCampaignMaster();
            $gameCampaign->Company_id = $dataParams->Company_id;
            $gameCampaign->Game_id = $dataParams->Game_id;
            $gameCampaign->Game_configuration_id = $dataParams->Game_configuration_id;
            $gameCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $gameCampaign->Create_User_id = $dataParams->Enrollment_id;
            $gameCampaign->Campaign_name = $request->Campaign_name;
            $gameCampaign->Campaign_description = $request->Campaign_description;
            $gameCampaign->Campaign_type = (int)$request->Campaign_type;
            $gameCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $gameCampaign->From_date = $request->From_date;
            $gameCampaign->To_date = $request->To_date;
            $gameCampaign->Tier_flag = (int)$request->Tier_flag;
            $gameCampaign->Reward_flag = (int)$request->Reward_flag;
            $gameCampaign->Reward_points = (int)$request->Reward_points;
            $gameCampaign->Reward_percent = (int)$request->Reward_percent;
            $gameCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $gameCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $gameCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $gameCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $gameCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $gameCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $gameCampaign->operator = $request->operator;
            $gameCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $gameCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $gameCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $gameCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $gameCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $gameCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $gameCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $gameCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $gameCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $gameCampaign->Special_day = (int)$request->Special_day;
            $gameCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $gameCampaign->Schedule = (int)$request->Schedule;
            $gameCampaign->campaign_status = $request->campaign_status;
            $gameCampaign->Start_time = $request->Start_time;
            $gameCampaign->End_time = $request->End_time;
            $gameCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $gameCampaign->Discount = (int)$request->Discount;
            $gameCampaign->Discrete_amt = $request->Discrete_amt;
            $gameCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $gameCampaign->Spend_amount = (int)$request->Spend_amount;
            $gameCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $gameCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $gameCampaign->LBS_linked = (int)$request->LBS_linked;
            $gameCampaign->Benefit_description = $request->Benefit_description;
            $gameCampaign->Benefit_communication = $request->Benefit_communication;

            if($gameCampaign->From_date > $gameCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($gameCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            $gameCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $gameCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            return response()->json([
                'success' => true,
                'message' => 'Game Campaign Was Saved Successfully!',
                'data'     => $gameCampaign
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

    public function updateGameCampaign(Request $request, $id)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_game_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_game_master.Create_User_id')
                        ->join('lsl_game_company_configuration', 'lsl_enrollment_master.Enrollment_id', 'lsl_game_company_configuration.Create_User_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_game_master.Game_id', 'lsl_game_company_configuration.Game_configuration_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->where(['lsl_game_master.Game_id' => $request->Game_id])
                        ->where(['lsl_game_company_configuration.Game_configuration_id' => $request->Game_configuration_id])
                        ->first();
        //
        try
        {
            if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
            {
                $updateGameCampaign = LslCampaignMaster::findOrFail($id);
                $updateGameCampaign->Company_id = $dataParams->Company_id;
                $updateGameCampaign->Game_id = $dataParams->Game_id;
                $updateGameCampaign->Game_configuration_id = $dataParams->Game_configuration_id;
                $updateGameCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateGameCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateGameCampaign->Campaign_name = $request->Campaign_name;
                $updateGameCampaign->Campaign_description = $request->Campaign_description;
                $updateGameCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateGameCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateGameCampaign->From_date = $request->From_date;
                $updateGameCampaign->To_date = $request->To_date;
                $updateGameCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateGameCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateGameCampaign->Reward_points = (int)$request->Reward_points;
                $updateGameCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateGameCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateGameCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateGameCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateGameCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateGameCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateGameCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateGameCampaign->operator = $request->operator;
                $updateGameCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateGameCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateGameCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateGameCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateGameCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateGameCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateGameCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateGameCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateGameCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateGameCampaign->Special_day = (int)$request->Special_day;
                $updateGameCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateGameCampaign->Schedule = (int)$request->Schedule;
                $updateGameCampaign->campaign_status = $request->campaign_status;
                $updateGameCampaign->Start_time = $request->Start_time;
                $updateGameCampaign->End_time = $request->End_time;
                $updateGameCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateGameCampaign->Discount = (int)$request->Discount;
                $updateGameCampaign->Discrete_amt = $request->Discrete_amt;
                $updateGameCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateGameCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateGameCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateGameCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateGameCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateGameCampaign->Benefit_description = $request->Benefit_description;
                $updateGameCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateGameCampaign->From_date > $updateGameCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updateGameCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updateGameCampaign->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Game Campaign Was Updated Successfully!',
                    'data'     => $updateGameCampaign
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

    public function deleteGameCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteGameCamp = LslCampaignMaster::findOrFail($id);
            $deleteGameCamp->delete();

            return response()->json([
                'success' => true,
                'message' => 'Game Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Game Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
