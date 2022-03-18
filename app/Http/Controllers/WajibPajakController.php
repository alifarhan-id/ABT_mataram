<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WajibPajak;

class WajibPajakController extends Controller
{
    public function index()
    {
        return view('wajib-pajak.index');
    }

    public function doGetWajibPajakByNpwp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'npwp' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(["message"=>"error gais"], 400);
        }else{
            $npwp = $request->npwp;
            $data = WajibPajak::where('npwp','=',$npwp)->where('status_operasi','=','active')->get();

            return response()->json($data, 200);
        }


    }
}
