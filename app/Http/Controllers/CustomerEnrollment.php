<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Password;
use App\Models\LslEnrollmentMaster;
use App\Models\LslEnrollmentMaster2;
use Illuminate\Validation\Rules\Password;

class CustomerEnrollment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Post::with(['comments', 'comments.user'])->get();
        $customerEnrollment = DB::table('lsl_enrollment_master')
                        ->join('lsl_loyalty_program_master', 'lsl_enrollment_master.Loyalty_programme_id', '=', 'lsl_loyalty_program_master.Loyalty_program_id')
                        ->join('lsl_country_currency_master', 'lsl_enrollment_master.Country', '=', 'lsl_country_currency_master.Country_id')
                        ->join('lsl_region_master', 'lsl_enrollment_master.Region', '=', 'lsl_region_master.Region_id')
                        ->join('lsl_zone_master', 'lsl_enrollment_master.Zone', '=', 'lsl_zone_master.Zone_id')
                        ->join('lsl_state_master', 'lsl_enrollment_master.State', '=', 'lsl_state_master.State_id')
                        ->join('lsl_city_master', 'lsl_enrollment_master.City', '=', 'lsl_city_master.City_id')
                        //->join('lsl_loyalty_program_master', 'lsl_enrollment_master.Loyalty_programme_id', '=', 'lsl_loyalty_program_master.Loyalty_program_id')
                        ->select('lsl_enrollment_master.*', 'lsl_loyalty_program_master.Loyalty_program_name', 'lsl_country_currency_master.Country_name', 'lsl_region_master.Region_name', 'lsl_zone_master.Zone_name', 'lsl_state_master.State_name', 'lsl_city_master.City_name')
                        ->get();

        return response()->json([
            'total' => count($customerEnrollment),
            'data' => $customerEnrollment
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)  ///// CUSTOMER ENROLLMENT AT BRANCH
    {
        //$birthDate = date('m/d/Y');  Password::min(8)
        $this->validate($request, [
            'First_name' => 'required',
            'Last_name' => 'required',
            'Country' => 'required',
            'Region' => 'required',
            'Zone' => 'required',
            'State' => 'required',
            'City' => 'required',
            'Phone_no' => 'required',
            'email' => 'required|email',
            'Sex' => 'required',
            'Password' => ['required', Password::min(6)],
            'Birth_date' => 'date',
            'Membership_id' => 'required',
            'loyalty_programme' => 'required',
            'Photograph' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $fileName = time() . '.' . $request->Photograph->extension();
        $request->Photograph->storeAs('public/images', $fileName);

        $customerEnrollment = new LslEnrollmentMaster();
        $customerEnrollment->First_name = $request->First_name;
        $customerEnrollment->Middle_name = $request->Middle_name;
        $customerEnrollment->Last_name = $request->Last_name;
        $customerEnrollment->Address = $request->Address;
        $customerEnrollment->Country = $request->Country;
        $customerEnrollment->Region = $request->Region;
        $customerEnrollment->Zone = $request->Zone;
        $customerEnrollment->State = $request->State;
        $customerEnrollment->City = $request->City;
        $customerEnrollment->Phone_no = $request->Phone_no;
        $customerEnrollment->Sex = $request->Sex;
        $customerEnrollment->Profession = $request->Profession;
        $customerEnrollment->Communication_flag = $request->Communication_flag;
        $customerEnrollment->Loyalty_programme_id = $request->loyalty_programme;
        $customerEnrollment->User_Email_id = $request->email;
        $customerEnrollment->Password = $request->Password;
        $customerEnrollment->Birth_date = date('Y-m-d H:i:s'); //Birth_date;
        $customerEnrollment->Anniversary_date = $request->Anniversary_date;
        $customerEnrollment->Photograph = $fileName;
        $customerEnrollment->Referee_id = $request->Referee_id;
        $customerEnrollment->Employee_flag = $request->Employee_flag;
        $customerEnrollment->Employee_id = $request->Employee_id;
        $customerEnrollment->Membership_id = $request->Membership_id;
        $customerEnrollment->Referee_flag = $request->Referee_flag;

        if ($customerEnrollment->save())
        {
            return response()->json([
                'success' => true,
                'message' => 'Customer Enrollment Was Successfully Saved',
                'data' => $customerEnrollment
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'data' => 'Customer Enrollment Could Not Be Saved'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function customerEnrolFromBranch(Request $request) /// CUSTOMER ENROLLMENT AT BRANCH
    {
        $this->validate($request, [
            'First_name' => 'required',
            'Last_name' => 'required',
            'email' => 'required|email',
            'Phone_no' => 'required|numeric|min:4',
            'Account_number' => 'required|numeric|min:6',
            //'Country' => 'required'
        ]);

        $custEnrolFromBranch = new LslEnrollmentMaster();
        $custEnrolFromBranch->First_name = $request->First_name;
        $custEnrolFromBranch->Middle_name = $request->Middle_name;
        $custEnrolFromBranch->Last_name = $request->Last_name;
        $custEnrolFromBranch->User_Email_id = $request->email;
        $custEnrolFromBranch->Phone_no = $request->Phone_no;
        $custEnrolFromBranch->Account_number = $request->Account_number;
        //$custEnrolFromBranch->Country = $request->Country;

        if ($custEnrolFromBranch->save())
        {
            return response()->json([
                'success' => true,
                'message' => 'Customer Enrollment From Website Was Successfully Saved',
                'data' => $custEnrolFromBranch
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'data' => 'Customer Enrollment From Website Could Not Be Saved'
            ], 500);
        }
    }

    public function custEnrolFromBranch()
    {
        $allcustEnrolFromBranch = DB::table('lsl_enrollment_master')
                                    ->select('lsl_enrollment_master.First_name', 'lsl_enrollment_master.Middle_name', 'lsl_enrollment_master.Last_name', 'lsl_enrollment_master.User_Email_id', 'lsl_enrollment_master.Phone_no', 'lsl_enrollment_master.Account_number')
                                    ->where('lsl_enrollment_master.Account_number', '!=', '')
                                    ->get();

        return response()->json([
            'total' => count($allcustEnrolFromBranch),
            'data' => $allcustEnrolFromBranch
        ], 200);
    }

    public function addFamilyMemberFromWebsite(Request $request) ////  CUSTOMER FAMILY MEMBER ENROLLMENT FROM WEBSITE
    {
        $this->validate($request, [
            'First_name' => 'required',
            'Last_name' => 'required',
            'email' => 'required|email',
            'Phone_no' => 'required|numeric|min:4',
            'Family_redeem_limit' => 'required|numeric'
        ]);

        $custEnrolFromBranch = new LslEnrollmentMaster2();
        $custEnrolFromBranch->First_name = $request->First_name;
        $custEnrolFromBranch->Middle_name = $request->Middle_name;
        $custEnrolFromBranch->Last_name = $request->Last_name;
        $custEnrolFromBranch->User_Email_id = $request->email;
        $custEnrolFromBranch->Phone_no = $request->Phone_no;
        $custEnrolFromBranch->Family_redeem_limit = $request->Family_redeem_limit;
        $custEnrolFromBranch->save();

        $query = DB::insert("INSERT INTO lsl_enrollment_master (First_name, Last_name, User_Email_id, Phone_no, Family_redeem_limit) SELECT lsl_enrollment_master2.First_name, lsl_enrollment_master2.Last_name, lsl_enrollment_master2.User_Email_id, lsl_enrollment_master2.Phone_no, lsl_enrollment_master2.Family_redeem_limit FROM lsl_enrollment_master2 WHERE lsl_enrollment_master2.Enrollments_id='$custEnrolFromBranch->Enrollments_id'");
        //dd($query);
        if ($query == true)
        {
            return response()->json([
                'success' => true,
                'message' => 'Family Member At Branch Was Successfully Saved',
                'data' => $query
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'data' => 'Family Member At Branch Could Not Be Saved'
            ], 500);
        }
    }

    public function allFamilyMemberAtBranch()
    {
        $allFamilyMemAtBran = DB::table('lsl_enrollment_master2')
                                    ->select('lsl_enrollment_master2.First_name', 'lsl_enrollment_master2.Middle_name', 'lsl_enrollment_master2.Last_name', 'lsl_enrollment_master2.User_Email_id', 'lsl_enrollment_master2.Phone_no', 'lsl_enrollment_master2.Family_redeem_limit')
                                    ->where('lsl_enrollment_master2.Family_redeem_limit', '!=', '')
                                    ->get();

        return response()->json([
            'total' => count($allFamilyMemAtBran),
            'data' => $allFamilyMemAtBran
        ], 200);
    }

    public function getCustomerFromWebsite($nameOrPhoneNumber)
    {
        //$getMemberFromWebsite = LslEnrollmentMaster::select('*')
        $getMemberFromWebsite = DB::table('lsl_enrollment_master')
                                    ->select('lsl_enrollment_master.First_name', 'lsl_enrollment_master.Last_name', 'lsl_enrollment_master.User_Email_id', 'lsl_enrollment_master.Phone_no', 'lsl_enrollment_master.Account_number', 'lsl_enrollment_master.Membership_id')
                                    //->where('Enrollment_id', '=', $id)
                                    //->where('First_name', '=', $nameOrPhoneNumber)
                                    ->where('Phone_no', '=', $nameOrPhoneNumber)
                                    ->where('Membership_id', '=', NULL)
                                    ->where('Account_number', '!=', null)
                                    //->get();
                                    ->first();

        return response()->json([
            'success' => true,
            'data' => $getMemberFromWebsite
        ]);
    }

    public function assignMembershipIdToCustomerFromWebsite(Request $request, $id)
    {
        $this->validate($request, [
            'Membership_id' => 'required'
        ]);

        $assignMembershipId = LslEnrollmentMaster::findOrFail($id);
        $assignMembershipId->Membership_id = $request->Membership_id;
        $assignMembershipId->update();

        return response()->json([
            'success' => true,
            'data' => $assignMembershipId
        ]);

    }

    public function getFamilyMemberAtBranch($id)
    {
        $getMemberAtBranch = DB::table('lsl_enrollment_master')
                                    ->select('lsl_enrollment_master.First_name', 'lsl_enrollment_master.Middle_name', 'lsl_enrollment_master.Last_name', 'lsl_enrollment_master.User_Email_id', 'lsl_enrollment_master.Phone_no', 'lsl_enrollment_master.Family_redeem_limit', 'lsl_enrollment_master.Membership_id')
                                    ->where('Enrollment_id', '=', $id)
                                    //->where('lsl_enrollment_master.Family_redeem_limit', '!=', '')
                                    ->where('lsl_enrollment_master.Family_redeem_limit', '!=', null)
                                    ->where('Membership_id', '=', NULL)
                                    //->get();
                                    ->first();
        return response()->json([
            'success' => true,
            'data' => $getMemberAtBranch
        ]);
    }

    public function assignMembershipIdAtBranch(Request $request, $id)
    {
        $this->validate($request, [
            'Membership_id' => 'required'
        ]);

        $assignMembershipIdAt = LslEnrollmentMaster::findOrFail($id);
        $assignMembershipIdAt->Membership_id = $request->Membership_id;
        $assignMembershipIdAt->update();

        return response()->json([
            'success' => true,
            'data' => $assignMembershipIdAt
        ]);

    }

    public function handleMemberQuery($membership_id)   ////////  Handle Member Query
    {
        $handleMemberQueryId = DB::table('lsl_enrollment_master')
                                    ->select('First_name', 'Middle_name', 'Last_name', 'Membership_id', 'Phone_no', 'Birth_date')
                                    ->where('Membership_id', '=', $membership_id)
                                    ->where('Birth_date', '!=', null)
                                    ->where('Phone_no', '!=', null)
                                    //->get();
                                    ->first();
        return response()->json([
            'success' => true,
            'data' => $handleMemberQueryId
        ]);
    }

}
