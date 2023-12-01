<?php

namespace App\Http\Controllers;

use App\Models\GenerateToken;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Program $program)
    {
        return response()->json([
            'data' => $program->get()
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'program_name' => 'required',
            'slug' => 'required',
        ]);

        $program = new Program();
        $program->program_name = $request->program_name;
        $program->slug = $request->slug;
        if(isset($request->status))
        {
            $program->status = 1;
        } else {
            $program->status = 0;
        }
        $program->save();

        if ($program)
            return response()->json([
                'success' => true,
                'data' => $program
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, program could not be saved'
            ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show($program)
    {
        //dd($program);
        //return $program->get();
        $singleProg = Program::findOrFail($program);

        return response()->json([
            'success' => true,
            'data' => $singleProg
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $program)
    {
        $this->validate($request, [
            'program_name' => 'required',
            'slug' => 'required',
        ]);

        $program = Program::findOrFail($program);
        $program->program_name = $request->program_name;
        $program->slug = $request->slug;
        if(isset($request->status))
        {
            $program->status = 1;
        } else {
            $program->status = 0;
        }
        $program->save();

        if ($program)
            return response()->json([
                'success' => true,
                'data' => $program
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, program could not be saved'
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy($program)
    {
        $deleteProg = Program::findOrFail($program);
        $deleteProg->delete();
        return response()->json([
            'success' => true,
            //'data' => 'Program was deleted successfully' . $deleteProg
            'data' => 'Program was deleted successfully'
        ]);
    }

    public function generateRan()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // generate a pin based on 2 * 7 digits + a random character
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];

        // shuffle the result
        $string = str_shuffle($pin);

        return $string;

        //$randomNumber = random_int(1000, 9999);
        //dd($randomNumber);

        //$pin = mt_rand(1000000, 9999999);
    }

    public function generateToken(Request $request)
    {
        $this->validate($request, [
            'app_name' => 'required',
            //'token' => 'required'
        ]);

        $letters = 'AEKOTWZ';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chara = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charac = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $character = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = mt_rand(1000000, 9999999) . mt_rand(1000000, 9999999) 
        . $characters[rand(0, strlen($characters) - 1)] . $letters
        . $chara[rand(0, strlen($chara) - 1)] . $letters
        . $charac[rand(0, strlen($charac) - 1)] . $letters
        . $character[rand(0, strlen($character) - 1)];
        //dd($token);

        $geneToken = new GenerateToken();
        $geneToken->app_name = $request->app_name;
        $geneToken->token = $token;
        $geneToken->save();

        return response()->json([
            'success' => true,
            'data' => $geneToken
        ], 201);
    }

}

