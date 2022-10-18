<?php

namespace App\Http\Controllers\Administrator\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Library\Structure;
use Validator;

class SettingController extends Controller
{
    use Structure;
    
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return view('administrator.admin.settings');
    }

    /**
     * Show data in datatable.
     */
    public function data(Request $request){

        $data = Setting::orderBy('id', 'desc')->get();

        return datatables()->of($data)->setRowClass(function ($user) {
                    return 'setting-edit-btn';
        })->setRowAttr([
            'style' => 'cursor:pointer;',
            'data-value' => '{{$id}}|{{$key}}|{{$value}}',
        ])->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [ 
              'key' => 'required',  
              'value' => 'required',  
            ]);

        if ($validator->fails()) {
            return response()->json($this->structure(false, $validator->errors()->first()), 200);
        }
      
        try {
            
            $setting = FindOrFail($request->setting_id);
            $setting->value = $request->value;

            if($setting->save()){
                return response()->json($this->structure(true, 'Updated Successfully!'), 200);
            }
            return response()->json($this->structure(false, "No Changes!"), 200);
        } catch (\Throwable $th) {
            return response()->json($this->structure(false, "Internal server error!"), 200);
        }
    }
}
