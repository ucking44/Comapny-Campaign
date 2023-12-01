<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class BonusCampaignController extends Controller
{
    public function getBonusCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //
        //$bonusCampaign = DB::table('lsl_campaign_master')->where('Schedule', '!=', NULL)->get();
        $bonusCampaign = LslCampaignMaster::where('Transaction_amount', '!=', 0)
                                          ->where('Transaction_amt_flag', '=', 1)
                                          ->where('Reward_fix_amt_flag', '=', 0)
                                          ->where('Cumulative_amount', '=', 0)
                                          ->where('Recuring_campaign_flag', '=', 0)
                                          ->where('Fixed_amount', '=', 0)
                                          //->where('Reward_percent', '=', 0)
                                          ->where('Reward_points', '!=', 0)
                                          ->where('Spend_amt_flag', '=', 0)
                                          ->where('Max_reward_budget', '=', 0)
                                          //->where('Schedule', '!=', 0)
                                          //->orwhere('Schedule', '!=', null)
                                          ->get();
        //
        return response()->json([
            'success' => true,
            'total'  => count($bonusCampaign),
            'data'    => $bonusCampaign
        ]);

    }

    public function saveBonusCampaign(Request $request)
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
            DB::beginTransaction();

            $bonusCampaign = new LslCampaignMaster();
            //$bonusCampaign->Code_id = $dataParams->Code_id;
            $bonusCampaign->Company_id = $dataParams->Company_id;
            $bonusCampaign->Product_group_id = $dataParams->Product_group_id;
            $bonusCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $bonusCampaign->Transaction_id = $dataParams->Transaction_id;
            $bonusCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $bonusCampaign->branch_id = $dataParams->branch_id;
            $bonusCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $bonusCampaign->Create_User_id = $dataParams->Enrollment_id;
            $bonusCampaign->Tier_id = $dataParams->Tier_id;
            //$bonusCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $bonusCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $bonusCampaign->Campaign_name = $request->Campaign_name;
            $bonusCampaign->Campaign_description = $request->Campaign_description;
            $bonusCampaign->Campaign_type = (int)$request->Campaign_type;
            $bonusCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $bonusCampaign->From_date = $request->From_date;
            $bonusCampaign->To_date = $request->To_date;
            $bonusCampaign->Tier_flag = (int)$request->Tier_flag;
            $bonusCampaign->Active_flag = (int)$request->Active_flag;
            $bonusCampaign->Reward_flag = (int)$request->Reward_flag;
            $bonusCampaign->Reward_points = (int)$request->Reward_points;
            $bonusCampaign->Reward_percent = (int)$request->Reward_percent;
            $bonusCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $bonusCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $bonusCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $bonusCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $bonusCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $bonusCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $bonusCampaign->operator = $request->operator;
            $bonusCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $bonusCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $bonusCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $bonusCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $bonusCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $bonusCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $bonusCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $bonusCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $bonusCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $bonusCampaign->Special_day = (int)$request->Special_day;
            $bonusCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $bonusCampaign->Schedule = (int)$request->Schedule;
            $bonusCampaign->campaign_status = $request->campaign_status;
            $bonusCampaign->Start_time = $request->Start_time;
            $bonusCampaign->End_time = $request->End_time;
            $bonusCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $bonusCampaign->Discount = (int)$request->Discount;
            $bonusCampaign->Discrete_amt = $request->Discrete_amt;
            $bonusCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $bonusCampaign->Spend_amount = (int)$request->Spend_amount;
            $bonusCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $bonusCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $bonusCampaign->LBS_linked = (int)$request->LBS_linked;
            $bonusCampaign->Benefit_description = $request->Benefit_description;
            $bonusCampaign->Benefit_communication = $request->Benefit_communication;
            //dd($bonusCampaign);

            if($bonusCampaign->From_date > $bonusCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Greater Than To Date'
                ]);
            }

            if($bonusCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Less Than Now'
                ]);
            }

            $bonusCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $bonusCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $bonusCampaign->Campaign_id;
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
                'message' => 'Bonus Campaign Was Saved Successfully!',
                'data'     => $bonusCampaign
            ]);
        }
        catch (Exception $e)
        {
            DB::rollBack();

            // return response([
            //     'status' => 'failed',
            //     'message' => $e->getMessage()
            // ], 400);

            return response()->json([
                'status'  => 'Failed!',
                'message' => $e->getMessage()
            ], 400);
        }

    }

    public function updateBonusCampaign(Request $request, $id)
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
        try
        {
            DB::beginTransaction();

            if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
            {
                $updateBonusCampaign = LslCampaignMaster::findOrFail($id);
                //$updateBonusCampaign->Code_id = $dataParams->Code_id;
                $updateBonusCampaign->Company_id = $dataParams->Company_id;
                $updateBonusCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateBonusCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateBonusCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateBonusCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateBonusCampaign->branch_id = $dataParams->branch_id;
                $updateBonusCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateBonusCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateBonusCampaign->Tier_id = $dataParams->Tier_id;
                //$updateBonusCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateBonusCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateBonusCampaign->Campaign_name = $request->Campaign_name;
                $updateBonusCampaign->Campaign_description = $request->Campaign_description;
                $updateBonusCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateBonusCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateBonusCampaign->From_date = $request->From_date;
                $updateBonusCampaign->To_date = $request->To_date;
                $updateBonusCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateBonusCampaign->Active_flag = (int)$request->Active_flag;
                $updateBonusCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateBonusCampaign->Reward_points = (int)$request->Reward_points;
                $updateBonusCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateBonusCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateBonusCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateBonusCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateBonusCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateBonusCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateBonusCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateBonusCampaign->operator = $request->operator;
                $updateBonusCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateBonusCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateBonusCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateBonusCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateBonusCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateBonusCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateBonusCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateBonusCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateBonusCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateBonusCampaign->Special_day = (int)$request->Special_day;
                $updateBonusCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateBonusCampaign->Schedule = (int)$request->Schedule;
                $updateBonusCampaign->campaign_status = $request->campaign_status;
                $updateBonusCampaign->Start_time = $request->Start_time;
                $updateBonusCampaign->End_time = $request->End_time;
                $updateBonusCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateBonusCampaign->Discount = (int)$request->Discount;
                $updateBonusCampaign->Discrete_amt = $request->Discrete_amt;
                $updateBonusCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateBonusCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateBonusCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateBonusCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateBonusCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateBonusCampaign->Benefit_description = $request->Benefit_description;
                $updateBonusCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateBonusCampaign->From_date > $updateBonusCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Greater Than To Date'
                    ]);
                }

                if($updateBonusCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Less Than Now'
                    ]);
                }

                $updateBonusCampaign->save();

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateBonusCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Bonus Campaign Was Updated Successfully!',
                    'data'     => $updateBonusCampaign
                ]);

            }

            return response()->json([
                'message' => 'Game Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
            ]);
        }
        catch (Exception $e)
        {
            DB::rollBack();

            return response()->json([
                'status'  => 'Failed!',
                'message' => $e->getMessage()
            ]);
        }

    }

    public function deleteBonusCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteBonusCam = LslCampaignMaster::findOrFail($id);
            $deleteBonusCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bonus Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Bonus Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
