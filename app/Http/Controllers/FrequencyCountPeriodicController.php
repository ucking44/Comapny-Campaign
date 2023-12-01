<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class FrequencyCountPeriodicController extends Controller
{
    public function getFreqCountPeriodicCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //
        $freqCountPerCampaign = LslCampaignMaster::where('Reward_fix_frequency_flag', '!=', 0)->where('Recuring_campaign_flag', '=', 1)
                                                 ->where('Fixed_frequency_count', '!=', 0)->where('Max_reward_budget', '=', 0)
                                                 ->get();

        return response()->json([
            'success' => true,
            'total'  => count($freqCountPerCampaign),
            'data'    => $freqCountPerCampaign
        ]);

    }

    public function saveFreqCountPeriodicCampaign(Request $request)
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

            $freqCountPeriodicCampaign = new LslCampaignMaster();
            //$freqCountPeriodicCampaign->Code_id = $dataParams->Code_id;
            $freqCountPeriodicCampaign->Company_id = $dataParams->Company_id;
            $freqCountPeriodicCampaign->Product_group_id = $dataParams->Product_group_id;
            $freqCountPeriodicCampaign->Product_brand_id = $dataParams->Product_brand_id;
            $freqCountPeriodicCampaign->Transaction_id = $dataParams->Transaction_id;
            $freqCountPeriodicCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
            $freqCountPeriodicCampaign->branch_id = $dataParams->branch_id;
            $freqCountPeriodicCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $freqCountPeriodicCampaign->Create_User_id = $dataParams->Enrollment_id;
            $freqCountPeriodicCampaign->Tier_id = $dataParams->Tier_id;
            //$freqCountPeriodicCampaign->Benefit_partner_id = $dataParams->Partner_id;
            $freqCountPeriodicCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $freqCountPeriodicCampaign->Campaign_name = $request->Campaign_name;
            $freqCountPeriodicCampaign->Campaign_description = $request->Campaign_description;
            $freqCountPeriodicCampaign->Campaign_type = (int)$request->Campaign_type;
            $freqCountPeriodicCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $freqCountPeriodicCampaign->From_date = $request->From_date;
            $freqCountPeriodicCampaign->To_date = $request->To_date;
            $freqCountPeriodicCampaign->Tier_flag = (int)$request->Tier_flag;
            $freqCountPeriodicCampaign->Active_flag = (int)$request->Active_flag;
            $freqCountPeriodicCampaign->Reward_flag = (int)$request->Reward_flag;
            $freqCountPeriodicCampaign->Reward_points = (int)$request->Reward_points;
            $freqCountPeriodicCampaign->Reward_percent = (int)$request->Reward_percent;
            $freqCountPeriodicCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $freqCountPeriodicCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $freqCountPeriodicCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $freqCountPeriodicCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $freqCountPeriodicCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $freqCountPeriodicCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $freqCountPeriodicCampaign->operator = $request->operator;
            $freqCountPeriodicCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $freqCountPeriodicCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $freqCountPeriodicCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $freqCountPeriodicCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $freqCountPeriodicCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $freqCountPeriodicCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $freqCountPeriodicCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $freqCountPeriodicCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $freqCountPeriodicCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $freqCountPeriodicCampaign->Special_day = $request->Special_day;
            $freqCountPeriodicCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $freqCountPeriodicCampaign->Schedule = (int)$request->Schedule;
            $freqCountPeriodicCampaign->campaign_status = $request->campaign_status;
            $freqCountPeriodicCampaign->Start_time = $request->Start_time;
            $freqCountPeriodicCampaign->End_time = $request->End_time;
            $freqCountPeriodicCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $freqCountPeriodicCampaign->Discount = (int)$request->Discount;
            $freqCountPeriodicCampaign->Discrete_amt = $request->Discrete_amt;
            $freqCountPeriodicCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $freqCountPeriodicCampaign->Spend_amount = (int)$request->Spend_amount;
            $freqCountPeriodicCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $freqCountPeriodicCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $freqCountPeriodicCampaign->LBS_linked = (int)$request->LBS_linked;
            $freqCountPeriodicCampaign->Benefit_description = $request->Benefit_description;
            $freqCountPeriodicCampaign->Benefit_communication = $request->Benefit_communication;

            if($freqCountPeriodicCampaign->From_date > $freqCountPeriodicCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Greater Than To Date'
                ]);
            }

            if($freqCountPeriodicCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Date Cannot Be Less Than Now'
                ]);
            }

            $freqCountPeriodicCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $freqCountPeriodicCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $freqCountPeriodicCampaign->Campaign_id;
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
                'message' => 'Frequency Count Periodic Campaign Was Saved Successfully!',
                'data'     => $freqCountPeriodicCampaign
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

    public function updateFreqCountPerCampaign(Request $request, $id)
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
                $updateFreqCountPerCampaign = LslCampaignMaster::findOrFail($id);
                //$updateFreqCountPerCampaign->Code_id = $dataParams->Code_id;
                $updateFreqCountPerCampaign->Company_id = $dataParams->Company_id;
                $updateFreqCountPerCampaign->Product_group_id = $dataParams->Product_group_id;
                $updateFreqCountPerCampaign->Product_brand_id = $dataParams->Product_brand_id;
                $updateFreqCountPerCampaign->Transaction_id = $dataParams->Transaction_id;
                $updateFreqCountPerCampaign->Transaction_channel_id = $dataParams->Transaction_channel_id;
                $updateFreqCountPerCampaign->branch_id = $dataParams->branch_id;
                $updateFreqCountPerCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateFreqCountPerCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateFreqCountPerCampaign->Tier_id = $dataParams->Tier_id;
                //$updateFreqCountPerCampaign->Benefit_partner_id = $dataParams->Partner_id;
                $updateFreqCountPerCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateFreqCountPerCampaign->Campaign_name = $request->Campaign_name;
                $updateFreqCountPerCampaign->Campaign_description = $request->Campaign_description;
                $updateFreqCountPerCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateFreqCountPerCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateFreqCountPerCampaign->From_date = $request->From_date;
                $updateFreqCountPerCampaign->To_date = $request->To_date;
                $updateFreqCountPerCampaign->Tier_flag = (int)$request->Tier_flag;
                $updateFreqCountPerCampaign->Active_flag = (int)$request->Active_flag;
                $updateFreqCountPerCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateFreqCountPerCampaign->Reward_points = (int)$request->Reward_points;
                $updateFreqCountPerCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateFreqCountPerCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateFreqCountPerCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateFreqCountPerCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateFreqCountPerCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateFreqCountPerCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateFreqCountPerCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateFreqCountPerCampaign->operator = $request->operator;
                $updateFreqCountPerCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateFreqCountPerCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateFreqCountPerCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateFreqCountPerCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateFreqCountPerCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateFreqCountPerCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateFreqCountPerCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateFreqCountPerCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateFreqCountPerCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateFreqCountPerCampaign->Special_day = $request->Special_day;
                $updateFreqCountPerCampaign->Recuring_campaign_flag = $request->Recuring_campaign_flag;
                $updateFreqCountPerCampaign->Schedule = (int)$request->Schedule;
                $updateFreqCountPerCampaign->campaign_status = $request->campaign_status;
                $updateFreqCountPerCampaign->Start_time = $request->Start_time;
                $updateFreqCountPerCampaign->End_time = $request->End_time;
                $updateFreqCountPerCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateFreqCountPerCampaign->Discount = (int)$request->Discount;
                $updateFreqCountPerCampaign->Discrete_amt = $request->Discrete_amt;
                $updateFreqCountPerCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateFreqCountPerCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateFreqCountPerCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateFreqCountPerCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateFreqCountPerCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateFreqCountPerCampaign->Benefit_description = $request->Benefit_description;
                $updateFreqCountPerCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateFreqCountPerCampaign->From_date > $updateFreqCountPerCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Greater Than To Date'
                    ]);
                }

                if($updateFreqCountPerCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Date Cannot Be Less Than Now'
                    ]);
                }

                $updateFreqCountPerCampaign->save();

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateFreqCountPerCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Frequency Count Periodic Campaign Was Updated Successfully!',
                    'data'     => $updateFreqCountPerCampaign
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

    public function deleteFreqCountPerCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
        {
            $deleteFreqCountPerCam = LslCampaignMaster::findOrFail($id);
            $deleteFreqCountPerCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Frequency Count Periodic Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Frequency Count Periodic Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
