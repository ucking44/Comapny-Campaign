<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class FixedBudgetPeriodicCampaignController extends Controller
{
    public function getFixedBudgetPeriodicCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //

        $fixedBudgetPeriodicCampaign = DB::table('lsl_campaign_master')
                                   ->where('Transaction_amt_flag', '=', 1)->where('Transaction_amount', '!=', null)
                                   ->where('Reward_once_flag', '=', 1)->where('Reward_flag', '=', 1)
                                   ->where('Special_day', '=', 0)->where('Recuring_campaign_flag', '=', 1)
                                   ->where('Reward_points', '!=', 0)->where('Max_reward_budget', '!=', 0)
                                   ->where('Discrete_amt', '!=', 0)
                                   ->get();

        return response()->json([
            'success' => true,
            'total'  => count($fixedBudgetPeriodicCampaign),
            'data'    => $fixedBudgetPeriodicCampaign
        ]);

    }

    public function saveFixedBudgetPeriodicCampaign(Request $request)
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

            $fixedBudgetPeriodicCampaign = new LslCampaignMaster();
            //$fixedBudgetPeriodicCampaign->Code_id = $dataParams->Code_id;
            $fixedBudgetPeriodicCampaign->Company_id = $dataParams->Company_id;
            $fixedBudgetPeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
            $fixedBudgetPeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $fixedBudgetPeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
            $fixedBudgetPeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $fixedBudgetPeriodicCampaign->branch_id = $dataParams->branch_id;
            $fixedBudgetPeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $fixedBudgetPeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
            $fixedBudgetPeriodicCampaign->Tier_id = $dataParams->Tier_id;
            //$fixedBudgetPeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $fixedBudgetPeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $fixedBudgetPeriodicCampaign->Campaign_name = $request->Campaign_name;
            $fixedBudgetPeriodicCampaign->Campaign_description = $request->Campaign_description;
            $fixedBudgetPeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
            $fixedBudgetPeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $fixedBudgetPeriodicCampaign->From_date = $request->From_date;
            $fixedBudgetPeriodicCampaign->To_date = $request->To_date;
            $fixedBudgetPeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
            //$fixedBudgetPeriodicCampaign->Active_flag = $request->Active_flag;
            $fixedBudgetPeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
            $fixedBudgetPeriodicCampaign->Reward_points = (int)$request->Reward_points;
            $fixedBudgetPeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
            $fixedBudgetPeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $fixedBudgetPeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $fixedBudgetPeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $fixedBudgetPeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $fixedBudgetPeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $fixedBudgetPeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $fixedBudgetPeriodicCampaign->operator = $request->operator;
            $fixedBudgetPeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $fixedBudgetPeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $fixedBudgetPeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $fixedBudgetPeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $fixedBudgetPeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $fixedBudgetPeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $fixedBudgetPeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $fixedBudgetPeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $fixedBudgetPeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $fixedBudgetPeriodicCampaign->Special_day = (int)$request->Special_day;
            $fixedBudgetPeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $fixedBudgetPeriodicCampaign->Schedule = (int)$request->Schedule;
            $fixedBudgetPeriodicCampaign->campaign_status = $request->campaign_status;
            $fixedBudgetPeriodicCampaign->Start_time = $request->Start_time;
            $fixedBudgetPeriodicCampaign->End_time = $request->End_time;
            $fixedBudgetPeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $fixedBudgetPeriodicCampaign->Discount = (int)$request->Discount;
            $fixedBudgetPeriodicCampaign->Discrete_amt = $request->Discrete_amt;
            $fixedBudgetPeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $fixedBudgetPeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
            $fixedBudgetPeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $fixedBudgetPeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $fixedBudgetPeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
            $fixedBudgetPeriodicCampaign->Benefit_description = $request->Benefit_description;
            $fixedBudgetPeriodicCampaign->Benefit_communication = $request->Benefit_communication;

            if($fixedBudgetPeriodicCampaign->From_date > $fixedBudgetPeriodicCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($fixedBudgetPeriodicCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            $fixedBudgetPeriodicCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $fixedBudgetPeriodicCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $fixedBudgetPeriodicCampaign->Campaign_id;
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
                'message' => 'Fixed Budget Periodic Campaign Was Saved Successfully!',
                'data'     => $fixedBudgetPeriodicCampaign
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

    public function updateFixedBudgetPeriodicCampaign(Request $request, $id)
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
                $updateFixedBudgetPeriodicCampaign = LslCampaignMaster::findOrFail($id);
                //$updateFixedBudgetPeriodicCampaign->Code_id = $dataParams->Code_id;
                $updateFixedBudgetPeriodicCampaign->Company_id = $dataParams->Company_id;
                $updateFixedBudgetPeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateFixedBudgetPeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateFixedBudgetPeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateFixedBudgetPeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateFixedBudgetPeriodicCampaign->branch_id = $dataParams->branch_id;
                $updateFixedBudgetPeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateFixedBudgetPeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateFixedBudgetPeriodicCampaign->Tier_id = $dataParams->Tier_id;
                //$updateFixedBudgetPeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateFixedBudgetPeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateFixedBudgetPeriodicCampaign->Campaign_name = $request->Campaign_name;
                $updateFixedBudgetPeriodicCampaign->Campaign_description = $request->Campaign_description;
                $updateFixedBudgetPeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateFixedBudgetPeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateFixedBudgetPeriodicCampaign->From_date = $request->From_date;
                $updateFixedBudgetPeriodicCampaign->To_date = $request->To_date;
                $updateFixedBudgetPeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
                //$updateFixedBudgetPeriodicCampaign->Active_flag = $request->Active_flag;
                $updateFixedBudgetPeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateFixedBudgetPeriodicCampaign->Reward_points = (int)$request->Reward_points;
                $updateFixedBudgetPeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateFixedBudgetPeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateFixedBudgetPeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateFixedBudgetPeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateFixedBudgetPeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateFixedBudgetPeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateFixedBudgetPeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateFixedBudgetPeriodicCampaign->operator = $request->operator;
                $updateFixedBudgetPeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateFixedBudgetPeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateFixedBudgetPeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateFixedBudgetPeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateFixedBudgetPeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateFixedBudgetPeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateFixedBudgetPeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateFixedBudgetPeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateFixedBudgetPeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateFixedBudgetPeriodicCampaign->Special_day = (int)$request->Special_day;
                $updateFixedBudgetPeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateFixedBudgetPeriodicCampaign->Schedule = (int)$request->Schedule;
                $updateFixedBudgetPeriodicCampaign->campaign_status = $request->campaign_status;
                $updateFixedBudgetPeriodicCampaign->Start_time = $request->Start_time;
                $updateFixedBudgetPeriodicCampaign->End_time = $request->End_time;
                $updateFixedBudgetPeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateFixedBudgetPeriodicCampaign->Discount = (int)$request->Discount;
                $updateFixedBudgetPeriodicCampaign->Discrete_amt = $request->Discrete_amt;
                $updateFixedBudgetPeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateFixedBudgetPeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateFixedBudgetPeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateFixedBudgetPeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateFixedBudgetPeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateFixedBudgetPeriodicCampaign->Benefit_description = $request->Benefit_description;
                $updateFixedBudgetPeriodicCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateFixedBudgetPeriodicCampaign->From_date > $updateFixedBudgetPeriodicCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updateFixedBudgetPeriodicCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updateFixedBudgetPeriodicCampaign->save();

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateFixedBudgetPeriodicCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Fixed Budget Periodic Campaign Was Updated Successfully!',
                    'data'     => $updateFixedBudgetPeriodicCampaign
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

    public function deleteFixedBudgetPeriodicCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $delFixBudPerCam = LslCampaignMaster::findOrFail($id);
            $delFixBudPerCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fixed Budget Periodic Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Fixed Budget Periodic Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }

}
