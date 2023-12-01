<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class CashBackPeriodicController extends Controller
{
    public function getcashBackPeriodicCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //

        $cashBackPeriodicCampaign = DB::table('lsl_campaign_master')
                                      ->where('Transaction_amt_flag', '=', 1)->where('Transaction_amount', '!=', null)
                                      ->where('Reward_percent', '!=', null)
                                      ->where('Special_day', '=', 0)->where('Recuring_campaign_flag', '=', 1)
                                      ->get();

        return response()->json([
            'success' => true,
            'total'  => count($cashBackPeriodicCampaign),
            'data'    => $cashBackPeriodicCampaign
        ]);

    }

    public function saveCashBackPeriodicCampaign(Request $request)
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

            $cashBackPeriodicCampaign = new LslCampaignMaster();
            //$cashBackPeriodicCampaign->Code_id = $dataParams->Code_id;
            $cashBackPeriodicCampaign->Company_id = $dataParams->Company_id;
            $cashBackPeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
            $cashBackPeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $cashBackPeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
            $cashBackPeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $cashBackPeriodicCampaign->branch_id = $dataParams->branch_id;
            $cashBackPeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $cashBackPeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
            $cashBackPeriodicCampaign->Tier_id = $dataParams->Tier_id;
            //$cashBackPeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $cashBackPeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $cashBackPeriodicCampaign->Campaign_name = $request->Campaign_name;
            $cashBackPeriodicCampaign->Campaign_description = $request->Campaign_description;
            $cashBackPeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
            $cashBackPeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $cashBackPeriodicCampaign->From_date = $request->From_date;
            $cashBackPeriodicCampaign->To_date = $request->To_date;
            $cashBackPeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
            $cashBackPeriodicCampaign->Active_flag = (int)$request->Active_flag;
            $cashBackPeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
            $cashBackPeriodicCampaign->Reward_points = (int)$request->Reward_points;
            $cashBackPeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
            $cashBackPeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $cashBackPeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $cashBackPeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $cashBackPeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $cashBackPeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $cashBackPeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $cashBackPeriodicCampaign->operator = $request->operator;
            $cashBackPeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $cashBackPeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $cashBackPeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $cashBackPeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $cashBackPeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $cashBackPeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $cashBackPeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $cashBackPeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $cashBackPeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $cashBackPeriodicCampaign->Special_day = (int)$request->Special_day;
            $cashBackPeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $cashBackPeriodicCampaign->Schedule = (int)$request->Schedule;
            $cashBackPeriodicCampaign->campaign_status = $request->campaign_status;
            $cashBackPeriodicCampaign->Start_time = $request->Start_time;
            $cashBackPeriodicCampaign->End_time = $request->End_time;
            $cashBackPeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $cashBackPeriodicCampaign->Discount = (int)$request->Discount;
            $cashBackPeriodicCampaign->Discrete_amt = $request->Discrete_amt;
            $cashBackPeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $cashBackPeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
            $cashBackPeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $cashBackPeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $cashBackPeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
            $cashBackPeriodicCampaign->Benefit_description = $request->Benefit_description;
            $cashBackPeriodicCampaign->Benefit_communication = $request->Benefit_communication;

            if($cashBackPeriodicCampaign->From_date > $cashBackPeriodicCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($cashBackPeriodicCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            ///dd($cashBackPeriodicCampaign);
            $cashBackPeriodicCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $cashBackPeriodicCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $cashBackPeriodicCampaign->Campaign_id;
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
                'message' => 'Cash Back Periodic Campaign Was Saved Successfully!',
                'data'     => $cashBackPeriodicCampaign
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

    public function updateCashBackPeriodicCampaign(Request $request, $id)
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
                $updateCashBackPeriodicCampaign = LslCampaignMaster::findOrFail($id);
                //$updateCashBackPeriodicCampaign->Code_id = $dataParams->Code_id;
                $updateCashBackPeriodicCampaign->Company_id = $dataParams->Company_id;
                $updateCashBackPeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateCashBackPeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateCashBackPeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateCashBackPeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateCashBackPeriodicCampaign->branch_id = $dataParams->branch_id;
                $updateCashBackPeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateCashBackPeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateCashBackPeriodicCampaign->Tier_id = $dataParams->Tier_id;
                //$updateCashBackPeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateCashBackPeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateCashBackPeriodicCampaign->Campaign_name = $request->Campaign_name;
                $updateCashBackPeriodicCampaign->Campaign_description = $request->Campaign_description;
                $updateCashBackPeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateCashBackPeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateCashBackPeriodicCampaign->From_date = $request->From_date;
                $updateCashBackPeriodicCampaign->To_date = $request->To_date;
                $updateCashBackPeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateCashBackPeriodicCampaign->Active_flag = (int)$request->Active_flag;
                $updateCashBackPeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateCashBackPeriodicCampaign->Reward_points = (int)$request->Reward_points;
                $updateCashBackPeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateCashBackPeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateCashBackPeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateCashBackPeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateCashBackPeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateCashBackPeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateCashBackPeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateCashBackPeriodicCampaign->operator = $request->operator;
                $updateCashBackPeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateCashBackPeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateCashBackPeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateCashBackPeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateCashBackPeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateCashBackPeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateCashBackPeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateCashBackPeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateCashBackPeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateCashBackPeriodicCampaign->Special_day = (int)$request->Special_day;
                $updateCashBackPeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateCashBackPeriodicCampaign->Schedule = (int)$request->Schedule;
                $updateCashBackPeriodicCampaign->campaign_status = $request->campaign_status;
                $updateCashBackPeriodicCampaign->Start_time = $request->Start_time;
                $updateCashBackPeriodicCampaign->End_time = $request->End_time;
                $updateCashBackPeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateCashBackPeriodicCampaign->Discount = (int)$request->Discount;
                $updateCashBackPeriodicCampaign->Discrete_amt = $request->Discrete_amt;
                $updateCashBackPeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateCashBackPeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateCashBackPeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateCashBackPeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateCashBackPeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateCashBackPeriodicCampaign->Benefit_description = $request->Benefit_description;
                $updateCashBackPeriodicCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateCashBackPeriodicCampaign->From_date > $updateCashBackPeriodicCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updateCashBackPeriodicCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updateCashBackPeriodicCampaign->save();

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateCashBackPeriodicCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Cash Back Periodic Campaign Was Updated Successfully!',
                    'data'     => $updateCashBackPeriodicCampaign
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

    public function deleteCashBackPeriodicCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteCashBackPeriodicCam = LslCampaignMaster::findOrFail($id);
            $deleteCashBackPeriodicCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cash Back Periodic Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Cash Back Periodic Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
