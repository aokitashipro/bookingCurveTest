<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Bookingcurve;
use App\Testbooking;

//useしないと 自動的にnamespaceのパスが付与されるのでuse
use SplFileObject;

class BookingcurvesController extends Controller
{
    //
    // public function index(Request $request)
    // {
    //     $test_1 = "テスト";
        
    //     return view('welcome',compact('test_1'));  
    // }

    protected $bookingcurve = null;

     public function __construct(Bookingcurve $bookingcurve)
    {
        $this->bookingcurve = $bookingcurve;
    }


    public function index()
    {
        // $data = [];
        // $data['bookingcurve'] = $this->bookingcurve->all();
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
    // setlocaleを設定
    setlocale(LC_ALL, 'ja_JP.UTF-8');

    // アップロードしたファイルを取得
    // 'csv_file' はCSVファイルインポート画面の inputタグのname属性
    $uploaded_file = $request->file('csv_file');

    // アップロードしたファイルの絶対パスを取得
    $file_path = $request->file('csv_file')->path($uploaded_file);

    $file = new SplFileObject($file_path);
    $file->setFlags(SplFileObject::READ_CSV);

    //dd($file);

    $row_count = 1;
    foreach ($file as $row)
    {
        // 1行目のヘッダーは取り込まない
        if ($row_count > 1)
        {

            $ota = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
            $reserved_date = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
            $checkin_date = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
            $total_price = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
            
            $dataInsert = [$ota, $reserved_date, $checkin_date, $total_price];
            
            //var_dump($ota);
            //var_dump($reserved_date);
            //var_dump($checkin_date);
            //var_dump($total_price);

            TestBooking::insert(array(
                'ota' => $ota, 
                'reserved_date' => $reserved_date, 
                'checkin_date' => $checkin_date, 
                'total_price' => $total_price
            ));

        }
        $row_count++;
    }


    }

}