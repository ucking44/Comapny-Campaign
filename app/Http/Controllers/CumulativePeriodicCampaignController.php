<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class CumulativePeriodicCampaignController extends Controller
{
    public function getCumulativePeriodicCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //

        $cumulativePeriodicCampaign = DB::table('lsl_campaign_master')
                                        //->where('Max_reward_budget', '=', 0)
                                        ->where('Cumulative_amount', '!=', 0)
                                        ->where('Recuring_campaign_flag', '=', 1)
                                        ->get();
        return response()->json([
            'success' => true,
            'total'  => count($cumulativePeriodicCampaign),
            'data'    => $cumulativePeriodicCampaign
        ]);
    }

    public function saveCumulativePeriodicCampaign(Request $request)
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

            $cumulativePeriodicCampaign = new LslCampaignMaster();
            //$cumulativePeriodicCampaign->Code_id = $dataParams->Code_id;
            $cumulativePeriodicCampaign->Company_id = $dataParams->Company_id;
            $cumulativePeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
            $cumulativePeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $cumulativePeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
            $cumulativePeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $cumulativePeriodicCampaign->branch_id = $dataParams->branch_id;
            $cumulativePeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $cumulativePeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
            $cumulativePeriodicCampaign->Tier_id = $dataParams->Tier_id;
            //$cumulativePeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $cumulativePeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $cumulativePeriodicCampaign->Campaign_name = $request->Campaign_name;
            $cumulativePeriodicCampaign->Campaign_description = $request->Campaign_description;
            $cumulativePeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
            $cumulativePeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $cumulativePeriodicCampaign->From_date = $request->From_date;
            $cumulativePeriodicCampaign->To_date = $request->To_date;
            $cumulativePeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
            $cumulativePeriodicCampaign->Active_flag = (int)$request->Active_flag;
            $cumulativePeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
            $cumulativePeriodicCampaign->Reward_points = (int)$request->Reward_points;
            $cumulativePeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
            $cumulativePeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $cumulativePeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $cumulativePeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $cumulativePeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $cumulativePeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $cumulativePeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $cumulativePeriodicCampaign->operator = $request->operator;
            $cumulativePeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $cumulativePeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $cumulativePeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $cumulativePeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $cumulativePeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $cumulativePeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $cumulativePeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $cumulativePeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $cumulativePeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $cumulativePeriodicCampaign->Special_day = (int)$request->Special_day;
            $cumulativePeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $cumulativePeriodicCampaign->Schedule = (int)$request->Schedule;
            $cumulativePeriodicCampaign->campaign_status = $request->campaign_status;
            $cumulativePeriodicCampaign->Start_time = $request->Start_time;
            $cumulativePeriodicCampaign->End_time = $request->End_time;
            $cumulativePeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $cumulativePeriodicCampaign->Discount = (int)$request->Discount;
            $cumulativePeriodicCampaign->Discrete_amt = $request->Discrete_amt;
            $cumulativePeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $cumulativePeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
            $cumulativePeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $cumulativePeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $cumulativePeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
            $cumulativePeriodicCampaign->Benefit_description = $request->Benefit_description;
            $cumulativePeriodicCampaign->Benefit_communication = $request->Benefit_communication;

            if($cumulativePeriodicCampaign->From_date > $cumulativePeriodicCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Greater Than To Date'
                ]);
            }

            if($cumulativePeriodicCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Less Than Now'
                ]);
            }

            $cumulativePeriodicCampaign->save();  //// SAVE CUMULATIVE PERIODIC CAMPAIGN

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $cumulativePeriodicCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $cumulativePeriodicCampaign->Campaign_id;
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
                'message' => 'Cumulative Periodic Campaign Was Saved Successfully!',
                'data'     => $cumulativePeriodicCampaign
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

    public function updateCumulativePeriodicCampaign(Request $request, $id)
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
                $updateCumulativePeriodicCampaign = LslCampaignMaster::findOrFail($id);
                //$updateCumulativePeriodicCampaign->Code_id = $dataParams->Code_id;
                $updateCumulativePeriodicCampaign->Company_id = $dataParams->Company_id;
                $updateCumulativePeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateCumulativePeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateCumulativePeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateCumulativePeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateCumulativePeriodicCampaign->branch_id = $dataParams->branch_id;
                $updateCumulativePeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateCumulativePeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateCumulativePeriodicCampaign->Tier_id = $dataParams->Tier_id;
                //$updateCumulativePeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateCumulativePeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateCumulativePeriodicCampaign->Campaign_name = $request->Campaign_name;
                $updateCumulativePeriodicCampaign->Campaign_description = $request->Campaign_description;
                $updateCumulativePeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateCumulativePeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateCumulativePeriodicCampaign->From_date = $request->From_date;
                $updateCumulativePeriodicCampaign->To_date = $request->To_date;
                $updateCumulativePeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateCumulativePeriodicCampaign->Active_flag = (int)$request->Active_flag;
                $updateCumulativePeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateCumulativePeriodicCampaign->Reward_points = (int)$request->Reward_points;
                $updateCumulativePeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateCumulativePeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateCumulativePeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateCumulativePeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateCumulativePeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateCumulativePeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateCumulativePeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateCumulativePeriodicCampaign->operator = $request->operator;
                $updateCumulativePeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateCumulativePeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateCumulativePeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateCumulativePeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateCumulativePeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateCumulativePeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateCumulativePeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateCumulativePeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateCumulativePeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateCumulativePeriodicCampaign->Special_day = (int)$request->Special_day;
                $updateCumulativePeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateCumulativePeriodicCampaign->Schedule = (int)$request->Schedule;
                $updateCumulativePeriodicCampaign->campaign_status = $request->campaign_status;
                $updateCumulativePeriodicCampaign->Start_time = $request->Start_time;
                $updateCumulativePeriodicCampaign->End_time = $request->End_time;
                $updateCumulativePeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateCumulativePeriodicCampaign->Discount = (int)$request->Discount;
                $updateCumulativePeriodicCampaign->Discrete_amt = $request->Discrete_amt;
                $updateCumulativePeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateCumulativePeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateCumulativePeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateCumulativePeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateCumulativePeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateCumulativePeriodicCampaign->Benefit_description = $request->Benefit_description;
                $updateCumulativePeriodicCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateCumulativePeriodicCampaign->From_date > $updateCumulativePeriodicCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Greater Than To Date'
                    ]);
                }

                if($updateCumulativePeriodicCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Less Than Now'
                    ]);
                }
                //
                $updateCumulativePeriodicCampaign->save();

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateCumulativePeriodicCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Cumulative Periodic Campaign Was Updated Successfully!',
                    'data'     => $updateCumulativePeriodicCampaign
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

    public function deleteCumulativePeriodicCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteCumulativePeriodicCam = LslCampaignMaster::findOrFail($id);
            $deleteCumulativePeriodicCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cumulative Periodic Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Cumulative Periodic Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);

    }
}
