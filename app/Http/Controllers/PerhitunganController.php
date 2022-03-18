<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Bobot;
use App\Models\PengelolaanAirTanah;
use App\Models\HAB;
use App\Models\DataABT;
use DataTables;

class PerhitunganController extends Controller
{
    public function index()
    {
        return view('perhitungan.index');
    }
    public function doGetPerhitungan(Request $request)
    {
        if ($request->ajax()) {
            $data = DataABT::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

    }

    public function doHitung(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pemakaian' => 'required',
            'kelompok' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(["message"=>"error gais"], 400);
        }else{
            $pemakaian = $request->pemakaian;
            $kelompok  = $request->kelompok;
            $data = $this->doMagic($pemakaian, $kelompok);
            return response()->json($data, 200);
        }
    }


    public function doMagic($pemakaian, $kelompok)
    {
        $range1 = 500;
        $range2 = 1000;
        $range3 = 1500;
        $range4 = 2000;
        $range5 = 2001; //more than 2000
        $pengurangan = 0.4;

        $getBobot = Bobot::select('bobot')->first();
        $getPengelolaan = PengelolaanAirTanah::where('kelompok',$kelompok)->first();
        $getHAB = HAB::select('angka')->first();

        $bobot = $getBobot->bobot;
        $bobot = $bobot * 0.6; // get f(s)
        $HAB = $getHAB->angka; //get HAB

        if($pemakaian < 501)
        {
            $pengelolaan = [
                'kelompok' => $getPengelolaan->kelompok,
                'nilai_0_500'=> floatval($getPengelolaan->nilai_0_500),
                'nilai_501_1500'=> floatval($getPengelolaan->nilai_501_1500),
                'nilai_1501_3000'=> floatval($getPengelolaan->nilai_1501_3000),
                'nilai_3001_5000'=> floatval($getPengelolaan->nilai_3001_5000),
                'nilai_lebih_besar_dari_5000'=> floatval($getPengelolaan->nilai_lebih_besar_dari_5000),

            ]; //get f(p)

            $fna1 = $bobot + (floatval($pengelolaan['nilai_0_500'] * 0.4));
            $fna2 = $bobot + (floatval($pengelolaan['nilai_501_1500'] * 0.4));
            $fna3 = $bobot + (floatval($pengelolaan['nilai_1501_3000'] * 0.4));
            $fna4 = $bobot + (floatval($pengelolaan['nilai_3001_5000'] * 0.4));
            $fna5 = $bobot + (floatval($pengelolaan['nilai_lebih_besar_dari_5000'] * 0.4));

            $FNA = [
                'fna1'=>$fna1,
                'fna2'=>$fna2,
                'fna3'=>$fna3,
                'fna4'=>$fna4,
                'fna5'=>$fna5,
            ]; //get fna

            $HDA = [
                'hda1'=>$HAB * $FNA['fna1'],

            ];


            $NPA = [
                // 'npa1' => $range1 * $HDA['hda1']  // if we multiple with range not wit pemakaian
                'npa1' => $pemakaian * $HDA['hda1'],

            ];

            $total_npa = $NPA['npa1'];
            $tarif_pajak = $total_npa * 0.2;
            $abt = $tarif_pajak;
            $abt_final = $abt - ($abt * $pengurangan );
            $nilai_pajak_progresive = $abt / $pemakaian;

            $response = [
                'range_500'              => floatval($pemakaian),
                'range_1500'             => 0,
                'range_3000'             => 0,
                'range_5000'             => 0,
                'range_lebih_dari_5000'  => 0,
                'HAB'                    => $HAB,
                'fs'                     => $bobot,
                'fp1'                    => $pengelolaan['nilai_0_500'],
                'fp2'                    => $pengelolaan['nilai_501_1500'],
                'fp3'                    => $pengelolaan['nilai_1501_3000'],
                'fp4'                    => $pengelolaan['nilai_3001_5000'],
                'fp5'                    => $pengelolaan['nilai_lebih_besar_dari_5000'],
                'FNA1'                   => $FNA['fna1'],
                'FNA2'                   => $FNA['fna2'],
                'FNA3'                   => $FNA['fna3'],
                'FNA4'                   => $FNA['fna4'],
                'FNA5'                   => $FNA['fna5'],
                'HDA1'                   => $HDA['hda1'],
                'HDA2'                   => 0,
                'HDA3'                   => 0,
                'HDA4'                   => 0,
                'HDA5'                   => 0,
                'NPA1'                   => $NPA['npa1'],
                'NPA2'                   => 0,
                'NPA3'                   => 0,
                'NPA4'                   => 0,
                'NPA5'                   => 0,
                'total_npa'              => $total_npa,
                'tarif_pajak'            => $tarif_pajak,
                'abt'                    => $abt,
                'pengurangan'            => $pengurangan,
                'abt_final'              => $abt_final,
                'nilai_pajak_progresive' => round($nilai_pajak_progresive)
            ];

            return response()->json(['results' =>$response]);

        }elseif($pemakaian >500 && $pemakaian <= 1500)
        {

            $pengelolaan = [
                'kelompok' => $getPengelolaan->kelompok,
                'nilai_0_500'=> floatval($getPengelolaan->nilai_0_500),
                'nilai_501_1500'=> floatval($getPengelolaan->nilai_501_1500),
                'nilai_1501_3000'=> floatval($getPengelolaan->nilai_1501_3000),
                'nilai_3001_5000'=> floatval($getPengelolaan->nilai_3001_5000),
                'nilai_lebih_besar_dari_5000'=> floatval($getPengelolaan->nilai_lebih_besar_dari_5000),

            ]; //get f(p)

            $fna1 = $bobot + (floatval($pengelolaan['nilai_0_500'] * 0.4));
            $fna2 = $bobot + (floatval($pengelolaan['nilai_501_1500'] * 0.4));
            $fna3 = $bobot + (floatval($pengelolaan['nilai_1501_3000'] * 0.4));
            $fna4 = $bobot + (floatval($pengelolaan['nilai_3001_5000'] * 0.4));
            $fna5 = $bobot + (floatval($pengelolaan['nilai_lebih_besar_dari_5000'] * 0.4));

            $FNA = [
                'fna1'=>$fna1,
                'fna2'=>$fna2,
                'fna3'=>$fna3,
                'fna4'=>$fna4,
                'fna5'=>$fna5,
            ]; //get fna

            $HDA = [
                'hda1'=>$HAB * $FNA['fna1'],
                'hda2'=>$HAB * $FNA['fna2'],
            ];


            $NPA = [
                // 'npa1' => $range1 * $HDA['hda1']  // if we multiple with range not wit pemakaian
                'npa1' => $range1 * $HDA['hda1'],
                'npa2' => ($pemakaian - $range1) * $HDA['hda2'] //get value last NPA
            ];

            $total_npa = $NPA['npa1'] + $NPA['npa2'] ;
            $tarif_pajak = $total_npa * 0.2;
            $abt = $tarif_pajak;
            $abt_final = $abt - ($abt * $pengurangan );
            $nilai_pajak_progresive = $abt / $pemakaian;

            $response = [
                'range_500'              => $range1,
                'range_1500'             => $pemakaian - $range1,
                'range_3000'             => 0,
                'range_5000'             => 0,
                'range_lebih_dari_5000'  => 0,
                'HAB'                    => $HAB,
                'fs'                     => $bobot,
                'fp1'                    => $pengelolaan['nilai_0_500'],
                'fp2'                    => $pengelolaan['nilai_501_1500'],
                'fp3'                    => $pengelolaan['nilai_1501_3000'],
                'fp4'                    => $pengelolaan['nilai_3001_5000'],
                'fp5'                    => $pengelolaan['nilai_lebih_besar_dari_5000'],
                'FNA1'                   => $FNA['fna1'],
                'FNA2'                   => $FNA['fna2'],
                'FNA3'                   => $FNA['fna3'],
                'FNA4'                   => $FNA['fna4'],
                'FNA5'                   => $FNA['fna5'],
                'HDA1'                   => $HDA['hda1'],
                'HDA2'                   => $HDA['hda2'],
                'HDA3'                   => 0,
                'HDA4'                   => 0,
                'HDA5'                   => 0,
                'NPA1'                   => $NPA['npa1'],
                'NPA2'                   => $NPA['npa2'],
                'NPA3'                   => 0,
                'NPA4'                   => 0,
                'NPA5'                   => 0,
                'total_npa'              => $total_npa,
                'tarif_pajak'            => $tarif_pajak,
                'abt'                    => $abt,
                'pengurangan'            => $pengurangan,
                'abt_final'              => $abt_final,
                'nilai_pajak_progresive' => round($nilai_pajak_progresive)
            ];
            return response()->json(['results' =>$response]);

        }
        elseif($pemakaian >1500 && $pemakaian <= 3000)
        {

            $pengelolaan = [
                'kelompok' => $getPengelolaan->kelompok,
                'nilai_0_500'=> floatval($getPengelolaan->nilai_0_500),
                'nilai_501_1500'=> floatval($getPengelolaan->nilai_501_1500),
                'nilai_1501_3000'=> floatval($getPengelolaan->nilai_1501_3000),
                'nilai_3001_5000'=> floatval($getPengelolaan->nilai_3001_5000),
                'nilai_lebih_besar_dari_5000'=> floatval($getPengelolaan->nilai_lebih_besar_dari_5000),

            ]; //get f(p)

            $fna1 = $bobot + (floatval($pengelolaan['nilai_0_500'] * 0.4));
            $fna2 = $bobot + (floatval($pengelolaan['nilai_501_1500'] * 0.4));
            $fna3 = $bobot + (floatval($pengelolaan['nilai_1501_3000'] * 0.4));
            $fna4 = $bobot + (floatval($pengelolaan['nilai_3001_5000'] * 0.4));
            $fna5 = $bobot + (floatval($pengelolaan['nilai_lebih_besar_dari_5000'] * 0.4));

            $FNA = [
                'fna1'=>$fna1,
                'fna2'=>$fna2,
                'fna3'=>$fna3,
                'fna4'=>$fna4,
                'fna5'=>$fna5,
            ]; //get fna

            $HDA = [
                'hda1'=>$HAB * $FNA['fna1'],
                'hda2'=>$HAB * $FNA['fna2'],
                'hda3'=>$HAB * $FNA['fna3'],
            ];


            $NPA = [
                // 'npa1' => $range1 * $HDA['hda1']  // if we multiple with range not wit pemakaian
                'npa1' => $range1 * $HDA['hda1'],
                'npa2' => $range2 * $HDA['hda2'],
                'npa3' => ($pemakaian - ($range1 + $range2)) * $HDA['hda3']
            ];

            $total_npa = $NPA['npa1'] + $NPA['npa2'] + $NPA['npa3'] ;
            $tarif_pajak = $total_npa * 0.2;
            $abt = $tarif_pajak;
            $abt_final = $abt - ($abt * $pengurangan );
            $nilai_pajak_progresive = $abt / $pemakaian;


            $response = [
                'range_500'              => $range1,
                'range_1500'             => $range2,
                'range_3000'             => $pemakaian - ($range1 + $range2),
                'range_5000'             => 0,
                'range_lebih_dari_5000'  => 0,
                'HAB'                    => $HAB,
                'fs'                     => $bobot,
                'fp1'                    => $pengelolaan['nilai_0_500'],
                'fp2'                    => $pengelolaan['nilai_501_1500'],
                'fp3'                    => $pengelolaan['nilai_1501_3000'],
                'fp4'                    => $pengelolaan['nilai_3001_5000'],
                'fp5'                    => $pengelolaan['nilai_lebih_besar_dari_5000'],
                'FNA1'                   => $FNA['fna1'],
                'FNA2'                   => $FNA['fna2'],
                'FNA3'                   => $FNA['fna3'],
                'FNA4'                   => $FNA['fna4'],
                'FNA5'                   => $FNA['fna5'],
                'HDA1'                   => $HDA['hda1'],
                'HDA2'                   => $HDA['hda2'],
                'HDA3'                   => $HDA['hda3'],
                'HDA4'                   => 0,
                'HDA5'                   => 0,
                'NPA1'                   => $NPA['npa1'],
                'NPA2'                   => $NPA['npa2'],
                'NPA3'                   => $NPA['npa3'],
                'NPA4'                   => 0,
                'NPA5'                   => 0,
                'total_npa'              => $total_npa,
                'tarif_pajak'            => $tarif_pajak,
                'abt'                    => $abt,
                'pengurangan'            => $pengurangan,
                'abt_final'              => $abt_final,
                'nilai_pajak_progresive' => round($nilai_pajak_progresive)
            ];
            return response()->json(['results' =>$response]);

        }
        elseif($pemakaian >3000 && $pemakaian <= 5000)
        {

            $pengelolaan = [
                'kelompok' => $getPengelolaan->kelompok,
                'nilai_0_500'=> floatval($getPengelolaan->nilai_0_500),
                'nilai_501_1500'=> floatval($getPengelolaan->nilai_501_1500),
                'nilai_1501_3000'=> floatval($getPengelolaan->nilai_1501_3000),
                'nilai_3001_5000'=> floatval($getPengelolaan->nilai_3001_5000),
                'nilai_lebih_besar_dari_5000'=> floatval($getPengelolaan->nilai_lebih_besar_dari_5000),

            ]; //get f(p)

            $fna1 = $bobot + (floatval($pengelolaan['nilai_0_500'] * 0.4));
            $fna2 = $bobot + (floatval($pengelolaan['nilai_501_1500'] * 0.4));
            $fna3 = $bobot + (floatval($pengelolaan['nilai_1501_3000'] * 0.4));
            $fna4 = $bobot + (floatval($pengelolaan['nilai_3001_5000'] * 0.4));
            $fna5 = $bobot + (floatval($pengelolaan['nilai_lebih_besar_dari_5000'] * 0.4));

            $FNA = [
                'fna1'=>$fna1,
                'fna2'=>$fna2,
                'fna3'=>$fna3,
                'fna4'=>$fna4,
                'fna5'=>$fna5,
            ]; //get fna

            $HDA = [
                'hda1'=>$HAB * $FNA['fna1'],
                'hda2'=>$HAB * $FNA['fna2'],
                'hda3'=>$HAB * $FNA['fna3'],
                'hda4'=>$HAB * $FNA['fna4'],
            ];


            $NPA = [
                // 'npa1' => $range1 * $HDA['hda1']  // if we multiple with range not wit pemakaian
                'npa1' => $range1 * $HDA['hda1'],
                'npa2' => $range2 * $HDA['hda2'],
                'npa3' => $range3 * $HDA['hda3'],
                'npa4' => ($pemakaian - ($range1 + $range2 + $range3) ) * $HDA['hda4']
            ];

            $total_npa = $NPA['npa1'] + $NPA['npa2'] + $NPA['npa3'] + $NPA['npa4'] ;
            $tarif_pajak = $total_npa * 0.2;
            $abt = $tarif_pajak;
            $abt_final = $abt - ($abt * $pengurangan );
            $nilai_pajak_progresive = $abt / $pemakaian;

            $response = [
                'range_500'              => $range1,
                'range_1500'             => $range2,
                'range_3000'             => $range3,
                'range_5000'             => $pemakaian - ($range1 + $range2 + $range3),
                'range_lebih_dari_5000'  => 0,
                'HAB'                    => $HAB,
                'fs'                     => $bobot,
                'fp1'                    => $pengelolaan['nilai_0_500'],
                'fp2'                    => $pengelolaan['nilai_501_1500'],
                'fp3'                    => $pengelolaan['nilai_1501_3000'],
                'fp4'                    => $pengelolaan['nilai_3001_5000'],
                'fp5'                    => $pengelolaan['nilai_lebih_besar_dari_5000'],
                'FNA1'                   => $FNA['fna1'],
                'FNA2'                   => $FNA['fna2'],
                'FNA3'                   => $FNA['fna3'],
                'FNA4'                   => $FNA['fna4'],
                'FNA5'                   => $FNA['fna5'],
                'HDA1'                   => $HDA['hda1'],
                'HDA2'                   => $HDA['hda2'],
                'HDA3'                   => $HDA['hda3'],
                'HDA4'                   => $HDA['hda4'],
                'HDA5'                   => 0,
                'NPA1'                   => $NPA['npa1'],
                'NPA2'                   => $NPA['npa2'],
                'NPA3'                   => $NPA['npa3'],
                'NPA4'                   => $NPA['npa4'],
                'NPA5'                   => 0,
                'total_npa'              => $total_npa,
                'tarif_pajak'            => $tarif_pajak,
                'abt'                    => $abt,
                'pengurangan'            => $pengurangan,
                'abt_final'              => $abt_final,
                'nilai_pajak_progresive' => round($nilai_pajak_progresive)
            ];
            return response()->json(['results' =>$response]);

        }
        elseif($pemakaian > 5000)
        {

            $pengelolaan = [
                'kelompok' => $getPengelolaan->kelompok,
                'nilai_0_500'=> floatval($getPengelolaan->nilai_0_500),
                'nilai_501_1500'=> floatval($getPengelolaan->nilai_501_1500),
                'nilai_1501_3000'=> floatval($getPengelolaan->nilai_1501_3000),
                'nilai_3001_5000'=> floatval($getPengelolaan->nilai_3001_5000),
                'nilai_lebih_besar_dari_5000'=> floatval($getPengelolaan->nilai_lebih_besar_dari_5000),

            ]; //get f(p)

            $fna1 = $bobot + (floatval($pengelolaan['nilai_0_500'] * 0.4));
            $fna2 = $bobot + (floatval($pengelolaan['nilai_501_1500'] * 0.4));
            $fna3 = $bobot + (floatval($pengelolaan['nilai_1501_3000'] * 0.4));
            $fna4 = $bobot + (floatval($pengelolaan['nilai_3001_5000'] * 0.4));
            $fna5 = $bobot + (floatval($pengelolaan['nilai_lebih_besar_dari_5000'] * 0.4));

            $FNA = [
                'fna1'=>$fna1,
                'fna2'=>$fna2,
                'fna3'=>$fna3,
                'fna4'=>$fna4,
                'fna5'=>$fna5,
            ]; //get fna

            $HDA = [
                'hda1'=>$HAB * $FNA['fna1'],
                'hda2'=>$HAB * $FNA['fna2'],
                'hda3'=>$HAB * $FNA['fna3'],
                'hda4'=>$HAB * $FNA['fna4'],
                'hda5'=>$HAB * $FNA['fna5'],
            ];


            $NPA = [
                // 'npa1' => $range1 * $HDA['hda1']  // if we multiple with range not wit pemakaian
                'npa1' => $range1 * $HDA['hda1'],
                'npa2' => $range2 * $HDA['hda2'],
                'npa3' => $range3 * $HDA['hda3'],
                'npa4' => $range4 * $HDA['hda4'],
                'npa5' => ($pemakaian - ($range1 + $range2 + $range3 + $range4) ) * $HDA['hda5']
            ];

            $total_npa = $NPA['npa1'] + $NPA['npa2'] + $NPA['npa3'] + $NPA['npa4'] + $NPA['npa5'] ;
            $tarif_pajak = $total_npa * 0.2;
            $abt = $tarif_pajak;
            $abt_final = $abt - ($abt * $pengurangan );
            $nilai_pajak_progresive = $abt / $pemakaian;

            $response = [
                'range_500'              => $range1,
                'range_1500'             => $range2,
                'range_3000'             => $range3,
                'range_5000'             => $range4,
                'range_lebih_dari_5000'  => $pemakaian - ($range1 + $range2 +$range3 + $range4),
                'HAB'                    => $HAB,
                'fs'                     => $bobot,
                'fp1'                    => $pengelolaan['nilai_0_500'],
                'fp2'                    => $pengelolaan['nilai_501_1500'],
                'fp3'                    => $pengelolaan['nilai_1501_3000'],
                'fp4'                    => $pengelolaan['nilai_3001_5000'],
                'fp5'                    => $pengelolaan['nilai_lebih_besar_dari_5000'],
                'FNA1'                   => $FNA['fna1'],
                'FNA2'                   => $FNA['fna2'],
                'FNA3'                   => $FNA['fna3'],
                'FNA4'                   => $FNA['fna4'],
                'FNA5'                   => $FNA['fna5'],
                'HDA1'                   => $HDA['hda1'],
                'HDA2'                   => $HDA['hda2'],
                'HDA3'                   => $HDA['hda3'],
                'HDA4'                   => 0,
                'HDA5'                   => 0,
                'NPA1'                   => $NPA['npa1'],
                'NPA2'                   => $NPA['npa2'],
                'NPA3'                   => $NPA['npa3'],
                'NPA4'                   => 0,
                'NPA5'                   => 0,
                'total_npa'              => $total_npa,
                'tarif_pajak'            => $tarif_pajak,
                'abt'                    => $abt,
                'pengurangan'            => $pengurangan,
                'abt_final'              => $abt_final,
                'nilai_pajak_progresive' => round($nilai_pajak_progresive)
            ];
            return response()->json(['results' =>$response]);

        }

    }


    public function doCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'npwp'      => 'required',
            'tahun'     => 'required',
            'nama_wp'   => 'required',
            'masa'      => 'required',
            'tahun'     => 'required',
            'pemakaian' => 'required'
        ]);

        $isExist = DataABT::select('*')->where([
            ['npwp', '=',   $request->npwp],
            ['masa', '=',   $request->masa],
            ['tahun', '=',  $request->tahun]
        ])->exists();

       // dd($validator->fails());


        if($validator->fails() || $isExist){
            return response()->json(["success"=>false, "message" =>"Bad Request"], 400);
        }else{

            $dataABT = new DataABT();
            $dataABT->npwp                      = $request->npwp;
            $dataABT->nama_wp                   = $request->nama_wp;
            $dataABT->masa                      = $request->masa;
            $dataABT->tahun                     = $request->tahun;
            $dataABT->pemakaian                 = $request->pemakaian;
            $dataABT->jenis_usaha               = $request->jenis_usaha;
            $dataABT->kelompok                  = $request->kelompok;
            $dataABT->range_500                 = $request->range_500;
            $dataABT->range_1500                = $request->range_1500;
            $dataABT->range_3000                = $request->range_3000;
            $dataABT->range_5000                = $request->range_5000;
            $dataABT->range_lebih_dari_5000     = $request->range_lebih_dari_5000;
            $dataABT->hab                       = $request->hab;
            $dataABT->fs                        = $request->fs;
            $dataABT->tahun                     = $request->tahun;
            $dataABT->fp1                       = $request->fp1;
            $dataABT->fp2                       = $request->fp2;
            $dataABT->fp3                       = $request->fp3;
            $dataABT->fp4                       = $request->fp4;
            $dataABT->fp5                       = $request->fp5;
            $dataABT->npa1                      = $request->npa1;
            $dataABT->npa2                      = $request->npa2;
            $dataABT->npa3                      = $request->npa3;
            $dataABT->npa4                      = $request->npa4;
            $dataABT->npa5                      = $request->npa5;
            $dataABT->total_npa                 = $request->total_npa;
            $dataABT->tarif_pajak               = $request->tarif_pajak;
            $dataABT->nilai_pajak_progresive    = $request->nilai_progresive;
            $dataABT->abt                       = $request->abt;
            $dataABT->sanksi                    = $request->sangsi;
            $dataABT->bunga                     = $request->bunga;
            $dataABT->pengurangan               = $request->pengurangan;
            $dataABT->abt_final                 = $request->abt_final;
            $dataABT->tanggal_penetapan         = date('d');
            $dataABT->bulan_penetapan           = date('m');
            $dataABT->tahun_penetapan           = date('Y');
            $dataABT->user_penetapan            = "sitampan";
            $dataABT->save();
            return response()->json(["success"=>true, "message"=>"insert success"], 201);

        }
    }


}
