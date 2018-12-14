<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Bookingcurve;
use Excel;


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
        $data = [];
        $data['bookingcurve'] = $this->bookingcurve->all();
        return view('welcome', $data);
    }

    /**
     * CSVインポート
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $file = $request->file('csv_file');
        $reader = Excel::load($file->getRealPath())->get();

        $rows = $reader->toArray();

        // echo '<pre>';
        // var_dump($rows);
        // echo '</pre>';

         foreach ($rows as $row){
             if (!isset($row['予約サイト名'])) {
                 return redirect()->back();
             }

             $record = $this->bookingcurve->firstOrNew(['ota' => $row['予約サイト名']]);
             $record->name = $row['予約サイト名'];
             $record->save();
         }
         return redirect()->action('BookingcurvesController@index');
    }


}
