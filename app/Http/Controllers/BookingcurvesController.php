<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Bookingcurve;
use App\Testbooking;
use DB;

//useしないと 自動的にnamespaceのパスが付与されるのでuse
use SplFileObject;

class BookingcurvesController extends Controller
{

    protected $bookingcurve = null;

    public function __construct(Bookingcurve $bookingcurve)
    {
        $this->bookingcurve = $bookingcurve;
    }


    public function index()
    {
        return view('welcome');
    }

    /**
     * CSVインポート
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {

    //全件削除
    TestBooking::truncate();

    // setlocaleを設定
    setlocale(LC_ALL, 'ja_JP.UTF-8');

    // アップロードしたファイルを取得
    // 'csv_file' はCSVファイルインポート画面の inputタグのname属性
    $uploaded_file = $request->file('csv_file');

    // アップロードしたファイルの絶対パスを取得
    $file_path = $request->file('csv_file')->path($uploaded_file);

    $file = new SplFileObject($file_path);
    $file->setFlags(SplFileObject::READ_CSV);

    //配列の箱を用意
    $array = [];

    $row_count = 1;
    
    foreach ($file as $row)
    {

        // 最終行の処理
        if ($row === [null]) continue; 
        
        // 1行目のヘッダーは取り込まない
        if ($row_count > 1)
        {
        
            $ota = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
            $reserved_date = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
            $checkin_date = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
            $total_price = mb_convert_encoding($row[5], 'UTF-8', 'SJIS');
        
            $bookingdata_array = [
                'ota' => $ota, 
                'reserved_date' => $reserved_date, 
                'checkin_date' => $checkin_date, 
                'total_price' => $total_price
            ];

            array_push($array, $bookingdata_array);

                // TestBooking::insert(array(
                //     'ota' => $ota, 
                //     'reserved_date' => $reserved_date, 
                //     'checkin_date' => $checkin_date, 
                //     'total_price' => $total_price
                // ));
        }

        $row_count++;

    }
    
    //var_dump($array);

    $array_count = count($array);

    if ($array_count < 1){

        TestBooking::insert($array);

    } else {

        $number_of_array = $array_count / 2 ;
        
        $array_partial = array_chunk($array, $number_of_array);

        dd($array_partial);

        TestBooking::insert($array_partial);

    }

    return view('welcome');

    }

    public function export(Request $request)
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=bookingcurve.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function()
        {
            
            $createCsvFile = fopen('php://output', 'w');
            
            $columns = [
                'reserved_date',
                'checkin_date',
                'total_price',
            ];

            mb_convert_variables('SJIS-win', 'UTF-8', $columns);
    
            fputcsv($createCsvFile, $columns);

            $bookingCurve = DB::table('testbookings');

            $bookingCurveResults = $bookingCurve
                ->select(['reserved_date'
                , 'checkin_date' 
                ,DB::raw('sum(total_price) as total_price')])
                ->groupby('reserved_date')
                ->get();
    
            //$bookingCurveResults自体は取得できている
            //dd($bookingCurveResults);
    
            foreach ($bookingCurveResults as $row) { 
                $csv = [
                    $row->reserved_date,
                    $row->checkin_date,
                    $row->total_price,
                ];

                mb_convert_variables('SJIS-win', 'UTF-8', $csv);

                fputcsv($createCsvFile, $csv);
            }
            fclose($createCsvFile);
        };
        
        return response()->stream($callback, 200, $headers);
        
    }

}