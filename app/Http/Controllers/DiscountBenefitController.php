<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslCampaignMaster;
use Illuminate\Support\Facades\DB;
use App\Models\LslCampaignSchedule;

class DiscountBenefitController extends Controller
{
    public function getDiscountBenefitCampaign()
    {
        $updateActiveStatus = DB::table('lsl_campaign_master')
                                ->select('Campaign_id')
                                ->where('To_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);

        //
        $discountBenefitCampaign = DB::table('lsl_campaign_master')
                                   ->where('Transaction_amt_flag', '!=', 1)->where('Discount', '!=', null)
                                   ->where('Spend_amt_flag', '=', 'Yes')->where('Benefit_communication', '!=', null)
                                   ->where('Reward_points', '=', 0)->where('Sweepstake_flag', '=', 0)
                                   ->where('Sweepstake_ticket_limit', '=', 0)->where('Reward_once_flag', '=', 0)
                                   ->where('Max_reward_budget', '=', 0)->where('Benefit_partner_id', '!=', NULL)
                                   ->where('Spend_amt_flag', '=', 0)
                                   ->orwhere('Discount', '!=', 0)
                                   ->get();

        return response()->json([
            'success' => true,
            'total'  => count($discountBenefitCampaign),
            'data'    => $discountBenefitCampaign
        ]);

    }

    public function saveDiscountBenefitCampaign(Request $request)
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

            $discountBenefitCampaign = new LslCampaignMaster();
            //$discountBenefitCampaign->Code_id = $dataParams->Code_id;
            $discountBenefitCampaign->Company_id = $dataParams->Company_id;
            //$discountBenefitCampaign->branch_id = $dataParams->branch_id;
            $discountBenefitCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $discountBenefitCampaign->Create_User_id = $dataParams->Enrollment_id;
            $discountBenefitCampaign->Tier_id = $dataParams->Tier_id;
            $discountBenefitCampaign->Benefit_partner_id = $dataParams->Partner_id;
            //$discountBenefitCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $discountBenefitCampaign->Campaign_name = $request->Campaign_name;
            $discountBenefitCampaign->Campaign_description = $request->Campaign_description;
            $discountBenefitCampaign->Campaign_type = (int)$request->Campaign_type;
            $discountBenefitCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
            $discountBenefitCampaign->From_date = $request->From_date;
            $discountBenefitCampaign->To_date = $request->To_date;
            $discountBenefitCampaign->Tier_flag = (int)$request->Tier_flag;
            //$discountBenefitCampaign->Active_flag = $request->Active_flag;
            $discountBenefitCampaign->Reward_flag = (int)$request->Reward_flag;
            $discountBenefitCampaign->Reward_points = (int)$request->Reward_points;
            $discountBenefitCampaign->Reward_percent = (int)$request->Reward_percent;
            $discountBenefitCampaign->Cashback_percent = (int)$request->Cashback_percent;
            $discountBenefitCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $discountBenefitCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
            $discountBenefitCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
            $discountBenefitCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
            $discountBenefitCampaign->Transaction_amount = (int)$request->Transaction_amount;
            $discountBenefitCampaign->operator = $request->operator;
            $discountBenefitCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
            $discountBenefitCampaign->Fixed_amount = (int)$request->Fixed_amount;
            $discountBenefitCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
            $discountBenefitCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
            $discountBenefitCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
            $discountBenefitCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
            $discountBenefitCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
            $discountBenefitCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
            $discountBenefitCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
            $discountBenefitCampaign->Special_day = (int)$request->Special_day;
            $discountBenefitCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
            $discountBenefitCampaign->Schedule = (int)$request->Schedule;
            $discountBenefitCampaign->campaign_status = $request->campaign_status;
            $discountBenefitCampaign->Start_time = $request->Start_time;
            $discountBenefitCampaign->End_time = $request->End_time;
            $discountBenefitCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
            $discountBenefitCampaign->Discount = (int)$request->Discount;
            $discountBenefitCampaign->Discrete_amt = $request->Discrete_amt;
            $discountBenefitCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
            $discountBenefitCampaign->Spend_amount = (int)$request->Spend_amount;
            $discountBenefitCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
            $discountBenefitCampaign->Upgrade_privilege = $request->Upgrade_privilege;
            $discountBenefitCampaign->LBS_linked = (int)$request->LBS_linked;
            $discountBenefitCampaign->Benefit_description = $request->Benefit_description;
            $discountBenefitCampaign->Benefit_communication = $request->Benefit_communication;

            if($discountBenefitCampaign->From_date > $discountBenefitCampaign->To_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($discountBenefitCampaign->From_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            //dd($discountBenefitCampaign);
            $discountBenefitCampaign->save();

            $updateActiveStatus = DB::table('lsl_campaign_master')
                                    ->select('Campaign_id')
                                    ->where(['Campaign_id' => $discountBenefitCampaign->Campaign_id])
                                    ->where('To_date', '>=', Carbon::now()->toDateString())
                                    ->update(['Active_flag' => 1]);
            //

            $saveCampaignSchedule = new LslCampaignSchedule();
            $saveCampaignSchedule->Campaign_id = $discountBenefitCampaign->Campaign_id;
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
                'message' => 'Discount Benefit Campaign Was Saved Successfully!',
                'data'     => $discountBenefitCampaign
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

    public function updateDiscountBenefitCampaign(Request $request, $id)
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

            if(LslCampaignMaster::where('Campaign_id', '=', $id )->exists())
            {
                $updateDiscountBenefitCampaign = LslCampaignMaster::findOrFail($id);
                $updateDiscountBenefitCampaign->Company_id = $dataParams->Company_id;
                //$updateDiscountBenefitCampaign->branch_id = $dataParams->branch_id;
                $updateDiscountBenefitCampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updateDiscountBenefitCampaign->Create_User_id = $dataParams->Enrollment_id;
                $updateDiscountBenefitCampaign->Tier_id = $dataParams->Tier_id;
                $updateDiscountBenefitCampaign->Benefit_partner_id = $dataParams->Partner_id;
                //$updateDiscountBenefitCampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updateDiscountBenefitCampaign->Campaign_name = $request->Campaign_name;
                $updateDiscountBenefitCampaign->Campaign_description = $request->Campaign_description;
                $updateDiscountBenefitCampaign->Campaign_type = (int)$request->Campaign_type;
                $updateDiscountBenefitCampaign->Campaign_sub_type = (int)$request->Campaign_sub_type;
                $updateDiscountBenefitCampaign->From_date = $request->From_date;
                $updateDiscountBenefitCampaign->To_date = $request->To_date;
                $updateDiscountBenefitCampaign->Tier_flag = (int)$request->Tier_flag;
                //$updateDiscountBenefitCampaign->Active_flag = $request->Active_flag;
                $updateDiscountBenefitCampaign->Reward_flag = (int)$request->Reward_flag;
                $updateDiscountBenefitCampaign->Reward_points = (int)$request->Reward_points;
                $updateDiscountBenefitCampaign->Reward_percent = (int)$request->Reward_percent;
                $updateDiscountBenefitCampaign->Cashback_percent = (int)$request->Cashback_percent;
                $updateDiscountBenefitCampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updateDiscountBenefitCampaign->Sweepstake_ticket_limit = (int)$request->Sweepstake_ticket_limit;
                $updateDiscountBenefitCampaign->Reward_once_flag = (int)$request->Reward_once_flag;
                $updateDiscountBenefitCampaign->Transaction_amt_flag = (int)$request->Transaction_amt_flag;
                $updateDiscountBenefitCampaign->Transaction_amount = (int)$request->Transaction_amount;
                $updateDiscountBenefitCampaign->operator = $request->operator;
                $updateDiscountBenefitCampaign->Reward_fix_amt_flag = (int)$request->Reward_fix_amt_flag;
                $updateDiscountBenefitCampaign->Fixed_amount = (int)$request->Fixed_amount;
                $updateDiscountBenefitCampaign->First_iteration_percentage = (int)$request->First_iteration_percentage;
                $updateDiscountBenefitCampaign->Second_iteration_percentage = (int)$request->Second_iteration_percentage;
                $updateDiscountBenefitCampaign->Reward_fix_frequency_flag = (int)$request->Reward_fix_frequency_flag;
                $updateDiscountBenefitCampaign->Fixed_frequency_count = (int)$request->Fixed_frequency_count;
                $updateDiscountBenefitCampaign->Max_reward_budget = (int)$request->Max_reward_budget;
                $updateDiscountBenefitCampaign->Max_reward_budget_cust = (int)$request->Max_reward_budget_cust;
                $updateDiscountBenefitCampaign->Cumulative_amount = (int)$request->Cumulative_amount;
                $updateDiscountBenefitCampaign->Special_day = (int)$request->Special_day;
                $updateDiscountBenefitCampaign->Recuring_campaign_flag = (int)$request->Recuring_campaign_flag;
                $updateDiscountBenefitCampaign->Schedule = (int)$request->Schedule;
                $updateDiscountBenefitCampaign->campaign_status = $request->campaign_status;
                $updateDiscountBenefitCampaign->Start_time = $request->Start_time;
                $updateDiscountBenefitCampaign->End_time = $request->End_time;
                $updateDiscountBenefitCampaign->Spend_amt_flag = (int)$request->Spend_amt_flag;
                $updateDiscountBenefitCampaign->Discount = (int)$request->Discount;
                $updateDiscountBenefitCampaign->Discrete_amt = $request->Discrete_amt;
                $updateDiscountBenefitCampaign->Special_occasian_criteria = (int)$request->Special_occasian_criteria;
                $updateDiscountBenefitCampaign->Spend_amount = (int)$request->Spend_amount;
                $updateDiscountBenefitCampaign->Partner_subcategory_id = (int)$request->Partner_subcategory_id;
                $updateDiscountBenefitCampaign->Upgrade_privilege = $request->Upgrade_privilege;
                $updateDiscountBenefitCampaign->LBS_linked = (int)$request->LBS_linked;
                $updateDiscountBenefitCampaign->Benefit_description = $request->Benefit_description;
                $updateDiscountBenefitCampaign->Benefit_communication = $request->Benefit_communication;

                if($updateDiscountBenefitCampaign->From_date > $updateDiscountBenefitCampaign->To_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updateDiscountBenefitCampaign->From_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updateDiscountBenefitCampaign->save();

                $updateSurveyStatus = DB::table('lsl_campaign_master')
                                        ->select('Campaign_id')
                                        ->where(['Campaign_id' => $updateDiscountBenefitCampaign->Campaign_id])
                                        ->where('To_date', '>=', Carbon::now()->toDateString())
                                        ->update(['Active_flag' => 1]);
                //

                $updateCampaignSchedule = DB::table('lsl_campaign_schedule')
                                            ->select('lsl_campaign_schedule.id', 'lsl_campaign_schedule.Campaign_id')
                                            ->where(['lsl_campaign_schedule.Campaign_id' => $updateDiscountBenefitCampaign->Campaign_id])
                                            ->update(['Jan' => (int)$request->Jan, 'Feb' => (int)$request->Feb, 'Mar' => (int)$request->Mar, 'Apr' => (int)$request->Apr, 'May' => (int)$request->May, 'Jun' => (int)$request->Jun, 'Jul' => (int)$request->Jul, 'Aug' => (int)$request->Aug, 'Sep' => (int)$request->Sep, 'Oct' => (int)$request->Oct, 'Nov' => (int)$request->Nov, 'Dec' => (int)$request->Dec, 'Mon' => (int)$request->Mon, 'Tue' => (int)$request->Tue, 'Wed' => (int)$request->Wed, 'Thu' => (int)$request->Thu, 'Fri' => (int)$request->Fri, 'Sat' => (int)$request->Sat, 'Sun' => (int)$request->Sun, 'First_week' => (int)$request->First_week, 'Second_week' => (int)$request->Second_week, 'Third_week' => (int)$request->Third_week, 'Fourth_week' => (int)$request->Fourth_week, 'Start_time' => (int)$request->Start_time, 'End_time' => (int)$request->End_time]);
                //
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Discount Benefit Campaign Was Updated Successfully!',
                    'data'     => $updateDiscountBenefitCampaign
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

    public function deleteDiscountBenefitCampaign($id)
    {
        if(LslCampaignMaster::where('Campaign_id', '=', $id)->exists())
        {
            $deleteDiscountBenefitCam = LslCampaignMaster::findOrFail($id);
            $deleteDiscountBenefitCam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Discount Benefit Campaign Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Discount Benefit Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
