<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LslCodedecodeMaster;
use App\Models\LslCodedecodeTypeMaster;

class CodedecodeController extends Controller
{
    public function getAllCodedecodeType()
    {
        $allCodedecodeType = LslCodedecodeTypeMaster::get();

        return response()->json([
            'success' => true,
            'data' => $allCodedecodeType
        ]);
    }

    public function getAllCodedecode()
    {
        $allCodedecode = LslCodedecodeMaster::get();

        return response()->json([
            'success' => true,
            'data' => $allCodedecode
        ]);
    }

    public function saveCodedecodeType(Request $request)
    {
        $this->validate($request, [
            'Typecd_description' => 'required'
        ]);

        $codedecodeType = new LslCodedecodeTypeMaster();
        $codedecodeType->Typecd_description = $request->Typecd_description;
        $codedecodeType->save();

        return response()->json([
            'success' => true,
            'data' => $codedecodeType
        ]);

    }

    public function saveCodedecode(Request $request)
    {
        $codedecodeTypeId = DB::table('lsl_codedecode_type_master')
                              ->select('lsl_codedecode_type_master.Typecd_id', 'lsl_codedecode_type_master.Typecd_description')
                              ->where(['Typecd_id' => $request->Typecd_id])
                              ->first();

        $this->validate($request, [
            'Decode_description' => 'required'
        ]);

        $codedecode = new LslCodedecodeMaster();
        $codedecode->Typecd_id = $codedecodeTypeId->Typecd_id;
        $codedecode->Decode_description = $request->Decode_description;
        $codedecode->save();

        return response()->json([
            'success' => true,
            'data' => $codedecode
        ]);
    }

}
