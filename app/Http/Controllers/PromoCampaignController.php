<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LslPromoCampaign;
use Illuminate\Support\Facades\DB;
use App\Models\LslPromoCampaignTmp;

class PromoCampaignController extends Controller
{
    public function getPromoCampaign()
    {
        $updateActiveStatus = DB::table('lsl_promo_campaign')
                                ->select('Campaign_id')
                                ->where('End_date', '<', Carbon::now()->toDateString())
                                ->update(['Active_flag' => 0]);
        //

        $allPromoCampain = LslPromoCampaign::all();

        return response()->json([
            'success' => true,
            'total'   => count($allPromoCampain),
            'data'    => $allPromoCampain
        ]);
    }

    public function savePromoCampaign(Request $request)
    {
        $dataParams = DB::table('lsl_company_master')
                        //->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        //->join('lsl_partner_master', 'lsl_company_master.Company_id', 'lsl_partner_master.Company_id')
                        //->join('lsl_branch_master', 'lsl_company_master.Company_id', 'lsl_branch_master.Company_id')
                        ->join('lsl_sweepstake_master', 'lsl_company_master.Company_id', 'lsl_sweepstake_master.Company_id')
                        ->select('lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id', 'lsl_sweepstake_master.Sweepstake_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        //->where(['Membership_id' => $request->Membership_id])
                        //->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        //->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        ->orWhere(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
                        ->first();
        //
        //dd($dataParams);
        $this->validate($request, [
            //'Campaign_name' => 'required',
            'Sweepstake_ticket_limit' => 'required',
            'File_name' => 'mimes:csv,txt,xlx,xls,xlsx,pdf|max:2048'
        ]);

        try
        {
            DB::beginTransaction();

            $promoTmp = new LslPromoCampaignTmp();
            $promoTmp->Company_id = $dataParams->Company_id;
            $promoTmp->Points = intVal($request->Points);
            $promoTmp->Promo_code = $request->Promo_code;
            $promoTmp->Start_date = $request->Start_date;
            $promoTmp->End_date = $request->End_date;

            if($promoTmp->Start_date > $promoTmp->End_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($promoTmp->Start_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }
            //$promoTmp->campaign_status = $request->campaign_status getClientOriginalName; getRealPath()  getFilename

            if($request->hasFile('File_name'))
            {
                // //$fileName = time() . '.' . $request->File_name->getRealPath();
                // $request->File_name->storeAs('public/images', $fileName);

                $file = $request->file('File_name');
                $extention = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extention;
                //$file->move('uploads/files', $filename);
                $file->move(public_path('files'), $filename);
                $promoTmp->File_name = $filename;
                $promoTmp->save();
            }
            else{
                $promoTmp->File_name = NULL;
                $promoTmp->save();
            }

            $promocampaign = new LslPromoCampaign();
            $promocampaign->Company_id = $promoTmp->Company_id;
            $promocampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
            $promocampaign->Sweepstake_id = $dataParams->Sweepstake_id;
            $promocampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
            $promocampaign->Sweepstake_ticket_limit = intVal($request->Sweepstake_ticket_limit);
            $promocampaign->Points = intVal($request->Points);
            $promocampaign->Promo_code = $promoTmp->Promo_code;
            $promocampaign->Campaign_description = $request->Campaign_description;
            $promocampaign->Start_date = $promoTmp->Start_date;
            $promocampaign->End_date = $promoTmp->End_date;
            $promocampaign->campaign_status = $request->campaign_status;

            if($promocampaign->Start_date > $promocampaign->End_date)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                ]);
            }

            if($promocampaign->Start_date < Carbon::now()->toDateString())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'From Or Start Date Cannot Be Less Than Now'
                ]);
            }

            $promocampaign->File_name = $promoTmp->File_name;
            $promocampaign->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Promo Campaign Was Saved Successfully!',
                'data'    => $promocampaign
            ]);
        }
        catch (Exception $e)
        {
            DB::rollback();

            return response([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);

            //DB::rollback();
            //throw $e;
            //'message' => $e->getMessage(),
        }

    }

    public function updatePromoCampaign(Request $request, $id)
    {
        $dataParams = DB::table('lsl_company_master')
                        //->join('lsl_company_master', 'lsl_enrollment_master.Company_id', 'lsl_company_master.Company_id')
                        ->join('lsl_loyalty_program_master', 'lsl_company_master.Company_id', 'lsl_loyalty_program_master.Company_id')
                        //->join('lsl_partner_master', 'lsl_company_master.Company_id', 'lsl_partner_master.Company_id')
                        //->join('lsl_branch_master', 'lsl_company_master.Company_id', 'lsl_branch_master.Company_id')
                        ->join('lsl_sweepstake_master', 'lsl_company_master.Company_id', 'lsl_sweepstake_master.Company_id')
                        ->select('lsl_company_master.Company_id', 'lsl_loyalty_program_master.Loyalty_program_id', 'lsl_sweepstake_master.Sweepstake_id')
                        ->where(['lsl_company_master.Company_id' => $request->Company_id])
                        ->where(['lsl_loyalty_program_master.Loyalty_program_id' => $request->Loyalty_program_id])
                        //->where(['Membership_id' => $request->Membership_id])
                        //->where(['lsl_branch_master.branch_id' => $request->branch_id])
                        //->where(['lsl_partner_master.Partner_id' => $request->Partner_id])
                        ->orWhere(['lsl_sweepstake_master.Sweepstake_id' => $request->Sweepstake_id])
                        ->first();
        //
        //dd($dataParams);  DB::beginTransaction();
        try
        {
            DB::beginTransaction();

            if(LslPromoCampaignTmp::where('Campaign_id', '=', $id)->exists())
            {
                $updatePromoTmp = LslPromoCampaignTmp::findOrFail($id);
                //dd($updatePromoTmp);
                $updatePromoTmp->Company_id = $dataParams->Company_id;
                //dd($updatePromoTmp);
                $updatePromoTmp->Points = intVal($request->Points);
                $updatePromoTmp->Promo_code = $request->Promo_code;
                $updatePromoTmp->Start_date = $request->Start_date;
                $updatePromoTmp->End_date = $request->End_date;

                if($updatePromoTmp->Start_date > $updatePromoTmp->End_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updatePromoTmp->Start_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }
                //$updatePromoTmp->campaign_status = $request->campaign_status getClientOriginalName; getRealPath()  getFilename

                if($request->hasFile('File_name'))
                {
                    // //$fileName = time() . '.' . $request->File_name->getRealPath();
                    // $request->File_name->storeAs('public/images', $fileName);

                    $file = $request->file('File_name');
                    $extention = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extention;
                    //$file->move('uploads/files', $filename);
                    $file->move(public_path('files'), $filename);
                    $updatePromoTmp->File_name = $filename;
                    $updatePromoTmp->save();
                }
                else{
                    //$updatePromoTmp->File_name = NULL;
                    $updatePromoTmp->save();
                }
            }

            if(LslPromoCampaign::where('Campaign_id', '=', $id)->exists())
            {
                $updatePromocampaign = LslPromoCampaign::findOrFail($id);
                $updatePromocampaign->Company_id = $updatePromoTmp->Company_id;
                $updatePromocampaign->Loyalty_program_id = $dataParams->Loyalty_program_id;
                $updatePromocampaign->Sweepstake_id = $dataParams->Sweepstake_id;
                $updatePromocampaign->Sweepstake_flag = (int)$request->Sweepstake_flag;
                $updatePromocampaign->Sweepstake_ticket_limit = intVal($request->Sweepstake_ticket_limit);
                $updatePromocampaign->Points = intVal($request->Points);
                $updatePromocampaign->Promo_code = $updatePromoTmp->Promo_code;
                $updatePromocampaign->Campaign_description = $request->Campaign_description;
                $updatePromocampaign->Start_date = $updatePromoTmp->Start_date;
                $updatePromocampaign->End_date = $updatePromoTmp->End_date;
                $updatePromocampaign->campaign_status = $request->campaign_status;

                if($updatePromocampaign->Start_date > $updatePromocampaign->End_date)
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Greater Than To Or End Date'
                    ]);
                }

                if($updatePromocampaign->Start_date < Carbon::now()->toDateString())
                {
                    return response()->json([
                        'success' => true,
                        'message' => 'From Or Start Date Cannot Be Less Than Now'
                    ]);
                }

                $updatePromocampaign->File_name = $updatePromoTmp->File_name;
                $updatePromocampaign->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Promo Campaign Was Updated Successfully!',
                    'data'    => $updatePromocampaign
                ]);
            }

            return response()->json([
                'message' => 'Promo Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
            ]);
        }
        catch (Exception $e)
        {
            DB::rollback();

            return response([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);

            //DB::rollback();
            //throw $e;
        }
    }

    public function deletePromoCampaign($id)
    {
        if(LslPromoCampaignTmp::where('Campaign_id', '=', $id)->exists())
        {
            $deletePromoCam = LslPromoCampaignTmp::findOrFail($id);
            $deletePromoCam->delete();

            if(LslPromoCampaign::where('Campaign_id', '=', $id)->exists())
            {
                $deletePromoCam = LslPromoCampaign::findOrFail($id);
                $deletePromoCam->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Promo Campaign Campaign Was Deleted Successfully!'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Promo Campaign Template Campaign Was Deleted Successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Promo Campaign With The ID Of ' . '(' . $id . ')' . ' Does Not Exist'
        ]);
    }
}
