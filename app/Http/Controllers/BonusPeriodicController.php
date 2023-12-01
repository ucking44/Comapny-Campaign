<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class BonusPeriodicController extends Controller
{
    public function getBonusPeriodicCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //

        $bonusPeriodicCampaign = DB::table('lsl_campaign_master')
                                   ->where('Transaction_amt_flag', '=', 1)->where('Transaction_amount', '!=', null)
                                   ->where('Reward_once_flag', '=', 1)->where('Reward_flag', '=', 1)
                                   ->where('Special_day', '=', 0)->where('Recuring_campaign_flag', '=', 1)
                                   ->get();

        return response()->json([
            'success' => true,
            'total'  => count($bonusPeriodicCampaign),
            'data'    => $bonusPeriodicCampaign
        ]);

    }

    public function saveBonusPeriodicCampaign(Request $request)
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
            
            $bonusPeriodicCampaign = new LslCampaignMaster();
            //$bonusPeriodicCampaign->Code_id = $dataParams->Code_id;
            $bonusPeriodicCampaign->Company_id = $dataParams->Company_id;
            $bonusPeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
            $bonusPeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $bonusPeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
            $bonusPeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $bonusPeriodicCampaign->branch_id = $dataParams->branch_id;
            $bonusPeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $bonusPeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
            $bonusPeriodicCampaign->Tier_id = $dataParams->Tier_id;
            //$bonusPeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $bonusPeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $bonusPeriodicCampaign->Campaign_name = $request->Campaign_name;
            $bonusPeriodicCampaign->Campaign_description = $request->Campaign_description;
            $bonusPeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
            $bonusPeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $bonusPeriodicCampaign->From_date = $request->From_date;
            $bonusPeriodicCampaign->To_date = $request->To_date;
            $bonusPeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
            //$bonusPeriodicCampaign->Active_flag = $request->Active_flag;
            $bonusPeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
            $bonusPeriodicCampaign->Reward_points = (int)$request->Reward_points;
            $bonusPeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
            $bonusPeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $bonusPeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $bonusPeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $bonusPeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $bonusPeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $bonusPeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $bonusPeriodicCampaign->operator = $request->operator;
            $bonusPeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $bonusPeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $bonusPeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $bonusPeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $bonusPeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $bonusPeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $bonusPeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $bonusPeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $bonusPeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $bonusPeriodicCampaign->Special_day = (int)$request->Special_day;
            $bonusPeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $bonusPeriodicCampaign->Schedule = (int)$request->Schedule;
            $bonusPeriodicCampaign->campaign_status = $request->campaign_status;
            $bonusPeriodicCampaign->Start_time = $request->Start_time;
            $bonusPeriodicCampaign->End_time = $request->End_time;
            $bonusPeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $bonusPeriodicCampaign->Discount = (int)$request->Discount;
            $bonusPeriodicCampaign->Discrete_amt = $request->Discrete_amt;
            $bonusPeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $bonusPeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
            $bonusPeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $bonusPeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $bonusPeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
            $bonusPeriodicCampaign->Benefit_description = $request->Benefit_description;
            $bonusPeriodicCampaign->Benefit_communication = $request->Benefit_communication;

            if($bonusPeriodicCampaign->From_date > $bonusPeriodicCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($bonusPeriodicCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            ///dd($bonusPeriodicCampaign);
            $bonusPeriodicCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $bonusPeriodicCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $bonusPeriodicCampaign->Campaign_id;
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
                'message' => 'Bonus Periodic Campaign Was Saved Successfully!',
                'data'     => $bonusPeriodicCampaign
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

    public function updateBonusPeriodicCampaign(Request $request, $id)
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
            if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
            {
                $updateBonusPeriodicCampaign = LslCampaignMaster::findOrFail($id);
                //$updateBonusPeriodicCampaign->Code_id = $dataParams->Code_id;
                $updateBonusPeriodicCampaign->Company_id = $dataParams->Company_id;
                $updateBonusPeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateBonusPeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateBonusPeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateBonusPeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateBonusPeriodicCampaign->branch_id = $dataParams->branch_id;
                $updateBonusPeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateBonusPeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateBonusPeriodicCampaign->Tier_id = $dataParams->Tier_id;
                //$updateBonusPeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateBonusPeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateBonusPeriodicCampaign->Campaign_name = $request->Campaign_name;
                $updateBonusPeriodicCampaign->Campaign_description = $request->Campaign_description;
                $updateBonusPeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateBonusPeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateBonusPeriodicCampaign->From_date = $request->From_date;
                $updateBonusPeriodicCampaign->To_date = $request->To_date;
                $updateBonusPeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
                //$updateBonusPeriodicCampaign->Active_flag = $request->Active_flag;
                $updateBonusPeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateBonusPeriodicCampaign->Reward_points = (int)$request->Reward_points;
                $updateBonusPeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateBonusPeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateBonusPeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateBonusPeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateBonusPeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateBonusPeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateBonusPeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateBonusPeriodicCampaign->operator = $request->operator;
                $updateBonusPeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateBonusPeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateBonusPeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateBonusPeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateBonusPeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateBonusPeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateBonusPeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateBonusPeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateBonusPeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateBonusPeriodicCampaign->Special_day = (int)$request->Special_day;
                $updateBonusPeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateBonusPeriodicCampaign->Schedule = (int)$request->Schedule;
                $updateBonusPeriodicCampaign->campaign_status = $request->campaign_status;
                $updateBonusPeriodicCampaign->Start_time = $request->Start_time;
                $updateBonusPeriodicCampaign->End_time = $request->End_time;
                $updateBonusPeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateBonusPeriodicCampaign->Discount = (int)$request->Discount;
                $updateBonusPeriodicCampaign->Discrete_amt = $request->Discrete_amt;
                $updateBonusPeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateBonusPeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateBonusPeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateBonusPeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateBonusPeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateBonusPeriodicCampaign->Benefit_description = $request->Benefit_description;
                $updateBonusPeriodicCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateBonusPeriodicCampaign->From_date > $updateBonusPeriodicCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updateBonusPeriodicCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updateBonusPeriodicCampaign->save();

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateBonusPeriodicCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Bonus Periodic Campaign Was Updated Successfully!',
                    'data'     => $updateBonusPeriodicCampaign
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

    public function deleteBonusPeriodicCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteBonusPeriodicCam = LslCampaignMaster::findOrFail($id);
            $deleteBonusPeriodicCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bonus Periodic Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Bonus Periodic Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
