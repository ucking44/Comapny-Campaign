<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class UpgradePrivilegeController extends Controller
{
    public function getUpgradePrivilegeCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //
        $upgradePrivilegeCampaign = DB::table('lsl_campaign_master')
                                   ->where('Transaction_amt_flag', '=', 1)->where('Upgrade_privilege', '!=', null)
                                   ->where('Spend_amt_flag', '=', 0)->where('Upgrade_privilege', '!=', null)
                                   ->where('Special_day', '=', NULL)->where('Recuring_campaign_flag', '=', 0)
                                   ->get();

        return response()->json([
            'success' => true,
            'total'  => count($upgradePrivilegeCampaign),
            'data'    => $upgradePrivilegeCampaign
        ]);

    }

    public function saveUpgradePrivilegeCampaign(Request $request)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        //->join('lsl_codedecode_master', 'lsl_company_master.Company_type', 'lsl_codedecode_master.Code_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_tier_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_master.Create_User_id')
                        ->join('lsl_partner_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_partner_master.Create_User_id')
                        //->join('lsl_branch_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_branch_master.Create_User_id')
                        //->join('lsl_sweepstake_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_sweepstake_master.Create_user_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id', 'lsl_tier_master.Tier_id', 'lsl_partner_master.Partner_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->where(['lsl_tier_master.Tier_id' => $request->Tier_id])
                        //->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        ->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        //->where(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
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
            
            $upgradePrivilegeCampaign = new LslCampaignMaster();
            //$upgradePrivilegeCampaign->Code_id = $dataParams->Code_id;
            $upgradePrivilegeCampaign->Company_id = $dataParams->Company_id;
            //$upgradePrivilegeCampaign->branch_id = $dataParams->branch_id;
            $upgradePrivilegeCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $upgradePrivilegeCampaign->Create_User_id = $dataParams->Enrollment_id;
            $upgradePrivilegeCampaign->Tier_id = $dataParams->Tier_id;
            $upgradePrivilegeCampaign->Benefit_partner_id = $dataParams->Partner_id;
            //$upgradePrivilegeCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $upgradePrivilegeCampaign->Campaign_name = $request->Campaign_name;
            $upgradePrivilegeCampaign->Campaign_description = $request->Campaign_description;
            $upgradePrivilegeCampaign->Campaign_type = (int)$request->Campaign_type;
            $upgradePrivilegeCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $upgradePrivilegeCampaign->From_date = $request->From_date;
            $upgradePrivilegeCampaign->To_date = $request->To_date;
            $upgradePrivilegeCampaign->Tier_flag = (int)$request->Tier_flag;
            //$upgradePrivilegeCampaign->Active_flag = $request->Active_flag;
            $upgradePrivilegeCampaign->Reward_flag = (int)$request->Reward_flag;
            $upgradePrivilegeCampaign->Reward_points = (int)$request->Reward_points;
            $upgradePrivilegeCampaign->Reward_percent = (int)$request->Reward_percent;
            $upgradePrivilegeCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $upgradePrivilegeCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $upgradePrivilegeCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $upgradePrivilegeCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $upgradePrivilegeCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $upgradePrivilegeCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $upgradePrivilegeCampaign->operator = $request->operator;
            $upgradePrivilegeCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $upgradePrivilegeCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $upgradePrivilegeCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $upgradePrivilegeCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $upgradePrivilegeCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $upgradePrivilegeCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $upgradePrivilegeCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $upgradePrivilegeCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $upgradePrivilegeCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $upgradePrivilegeCampaign->Special_day = $request->Special_day;
            $upgradePrivilegeCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $upgradePrivilegeCampaign->Schedule = (int)$request->Schedule;
            $upgradePrivilegeCampaign->campaign_status = $request->campaign_status;
            $upgradePrivilegeCampaign->Start_time = $request->Start_time;
            $upgradePrivilegeCampaign->End_time = $request->End_time;
            $upgradePrivilegeCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $upgradePrivilegeCampaign->Discount = (int)$request->Discount;
            $upgradePrivilegeCampaign->Discrete_amt = $request->Discrete_amt;
            $upgradePrivilegeCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $upgradePrivilegeCampaign->Spend_amount = (int)$request->Spend_amount;
            $upgradePrivilegeCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $upgradePrivilegeCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $upgradePrivilegeCampaign->LBS_linked = (int)$request->LBS_linked;
            $upgradePrivilegeCampaign->Benefit_description = $request->Benefit_description;
            $upgradePrivilegeCampaign->Benefit_communication = $request->Benefit_communication;

            if($upgradePrivilegeCampaign->From_date > $upgradePrivilegeCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($upgradePrivilegeCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            //dd($upgradePrivilegeCampaign);
            $upgradePrivilegeCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $upgradePrivilegeCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $upgradePrivilegeCampaign->Campaign_id;
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
                'message' => 'Upgrade Privilege Campaign Was Saved Successfully!',
                'data'     => $upgradePrivilegeCampaign
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

    public function updateUpgradePrivilegeCampaign(Request $request, $id)
    {
        $dataParams = DB::table('lsl_enrollment_master')
                        ->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        ->join('lsl_tier_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_tier_master.Create_User_id')
                        ->join('lsl_partner_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_partner_master.Create_User_id')
                        //->join('lsl_branch_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_branch_master.Create_User_id')
                        //->join('lsl_sweepstake_master', 'lsl_enrollment_master.Enrollment_id', 'lsl_sweepstake_master.Create_user_id')
                        ->select('lsl_enrollment_master.Enrollment_id', 'lsl_enrollment_master.Membership_id', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id', 'lsl_tier_master.Tier_id', 'lsl_partner_master.Partner_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        ->where(['Membership_id' => $request->Membership_id])
                        ->where(['lsl_tier_master.Tier_id' => $request->Tier_id])
                        //->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        ->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        //->where(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
                        ->first();
        //
        try
        {
            DB::beginTransaction();

            if(LslCampaignMaster::where('Campaign_id', '=', $id)->exists())
            {
                $updateUpgradePrivilegeCampaign = LslCampaignMaster::findOrFail($id);
                $updateUpgradePrivilegeCampaign->Company_id = $dataParams->Company_id;
                //$updateUpgradePrivilegeCampaign->branch_id = $dataParams->branch_id;
                $updateUpgradePrivilegeCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateUpgradePrivilegeCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateUpgradePrivilegeCampaign->Tier_id = $dataParams->Tier_id;
                $updateUpgradePrivilegeCampaign->Benefit_partner_id = $dataParams->Partner_id;
                //$updateUpgradePrivilegeCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateUpgradePrivilegeCampaign->Campaign_name = $request->Campaign_name;
                $updateUpgradePrivilegeCampaign->Campaign_description = $request->Campaign_description;
                $updateUpgradePrivilegeCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateUpgradePrivilegeCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateUpgradePrivilegeCampaign->From_date = $request->From_date;
                $updateUpgradePrivilegeCampaign->To_date = $request->To_date;
                $updateUpgradePrivilegeCampaign->Tier_flag = (int)$request->Tier_flag;
                //$updateUpgradePrivilegeCampaign->Active_flag = $request->Active_flag;
                $updateUpgradePrivilegeCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateUpgradePrivilegeCampaign->Reward_points = (int)$request->Reward_points;
                $updateUpgradePrivilegeCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateUpgradePrivilegeCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateUpgradePrivilegeCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateUpgradePrivilegeCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateUpgradePrivilegeCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateUpgradePrivilegeCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateUpgradePrivilegeCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateUpgradePrivilegeCampaign->operator = $request->operator;
                $updateUpgradePrivilegeCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateUpgradePrivilegeCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateUpgradePrivilegeCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateUpgradePrivilegeCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateUpgradePrivilegeCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateUpgradePrivilegeCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateUpgradePrivilegeCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateUpgradePrivilegeCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateUpgradePrivilegeCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateUpgradePrivilegeCampaign->Special_day = $request->Special_day;
                $updateUpgradePrivilegeCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateUpgradePrivilegeCampaign->Schedule = (int)$request->Schedule;
                $updateUpgradePrivilegeCampaign->campaign_status = $request->campaign_status;
                $updateUpgradePrivilegeCampaign->Start_time = $request->Start_time;
                $updateUpgradePrivilegeCampaign->End_time = $request->End_time;
                $updateUpgradePrivilegeCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateUpgradePrivilegeCampaign->Discount = (int)$request->Discount;
                $updateUpgradePrivilegeCampaign->Discrete_amt = $request->Discrete_amt;
                $updateUpgradePrivilegeCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateUpgradePrivilegeCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateUpgradePrivilegeCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateUpgradePrivilegeCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateUpgradePrivilegeCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateUpgradePrivilegeCampaign->Benefit_description = $request->Benefit_description;
                $updateUpgradePrivilegeCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateUpgradePrivilegeCampaign->From_date > $updateUpgradePrivilegeCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updateUpgradePrivilegeCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updateUpgradePrivilegeCampaign->save();

                $updateSurveyStatus = DB::table('lsl_campaign_master')
                                        ->select('Campaign_id')
                                        ->where(['Campaign_id' => $updateUpgradePrivilegeCampaign->Campaign_id])
                                        ->where('To_date', '>=', Carbon::now()->toDateString())
                                        ->update(['Active_flag' => 1]);
                //

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateUpgradePrivilegeCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Upgrade Privilege Campaign Was Updated Successfully!',
                    'data'     => $updateUpgradePrivilegeCampaign
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

    public function deleteUpgradePrivilegeCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id)->exists())
        {
            $deleteUpgradePrivilegeCam = LslCampaignMaster::findOrFail($id);
            $deleteUpgradePrivilegeCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Upgrade Privilege Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Upgrade Privilege Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
