<?php

namespace App\Http\Controllers\Administrator\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Library\Structure;
use Validator;

class SubAdminController extends Controller
{
    use Structure;
    
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return view('administrator.admin.sub-admins');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [ 
              'name' => 'required',  
              'email' => 'required|email|unique:admins',  
              'phone' => 'required|numeric|unique:admins',  
              'password' => 'required|min:6',  
              'confirm_password' => 'required|same:password',  
            ]);

        if ($validator->fails()) {
            return response()->json($this->structure(false, $validator->errors()->first()), 200);
        }

        $admin = new Admin();

        $admin->name = ucwords($request->name);
        $admin->email = strtolower($request->email);
        $admin->phone = $request->phone;
        $admin->role = 'sub-admin';
        $admin->password = bcrypt($request->password);
        $admin->name = ucwords($request->name);

        try {
            $admin->save();
            return response()->json($this->structure(true, 'Sub Admin Added Successfully!'), 200);
        } catch (\Throwable $th) {
            return response()->json($this->structure(false, "Something went wrong, Try again!"), 200);
        }
    }

    /**
     * Show data in datatable.
     */
    public function data(Request $request){

        $data = Admin::where('role', 'sub-admin')->orderBy('id', 'desc')->get();

        return datatables()->of($data)->addColumn('action', function ($data) {

            if ($data->is_active == 'Yes') {
                $button = '<a href="javascript:void()" name="disable" data-value="'.$data->id.'" class="status-btn btn btn-sm btn-danger"><i class="bx bx-block"></i></a>';
            }else{
                $button = '<a href="javascript:void()" name="enable" data-value="'.$data->id.'" class="status-btn btn btn-sm btn-success"><i class="bx bx-check"></i></a>';
            }

            return $button;
        })->addColumn('status', function ($data) {
            if ($data->is_active == 'Yes') {
                return '<span class="badge badge-light-info">Active</span>';
            }
            return '<span class="badge badge-light-danger">In-Active</span>';
        })->addColumn('contact', function ($data) { 
            $contact = '-';
            if ($data->phone) {
                $contact = '<span style="display:block;" class="subadmin-contact"><i class="bx bxs-phone-call" style="position: relative;top: 3px;left: -5px;"></i><a href="tel:'.$data->phone.'" style="color:#0a187c;">'.$data->phone.'</a></span>';
            }
            if ($data->email) {
                 $contact .= '<span style="display:block;" class="subadmin-contact"><i class="bx bx-envelope" style="position: relative;top: 3px;left: -5px;"></i><a href="mailto:'.$data->email.'" style="color:#0a187c;">'.$data->email.'</a></span>';
            }
            return $contact;
        })->setRowClass(function ($user) {
                    return 'sub-admin-edit-btn';
        })->setRowAttr([
            'style' => 'cursor:pointer;',
            'data-value' => '{{$id}}|{{$name}}|{{$email}}|{{$phone}}',
        ])->rawColumns(['action', 'contact', 'status'])->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validator = Validator::make($request->all(), 
            [ 
              'name' => 'required',  
              'email' => 'required|email',  
              'phone' => 'required|numeric',
            ]);

        if ($validator->fails()) {
            return response()->json($this->structure(false, $validator->errors()->first()), 200);
        }

        if ($admin = Admin::find($request->sub_admin_id)) {

            if (Admin::where('id', '!=', $admin->id)->where('email', $request->email)->count()) {
                return response()->json($this->structure(false, "Email is already exist!"), 200);
            }
            if (Admin::where('id', '!=', $admin->id)->where('phone', $request->email)->count()) {
                return response()->json($this->structure(false, "Phone is already exist!"), 200);
            }

            $admin->name = ucwords($request->name);
            $admin->email = strtolower($request->email);
            $admin->phone = $request->phone;
            $admin->role = 'sub-admin';
            $admin->updated_at = date('Y-m-d h:i:s');

            try {
                
                if ($request->password) {
                
                    if ($request->password != $request->confirm_password) {
                        return response()->json($this->structure(false, "Passwword & confirm password must be match!"), 200);
                    }
                    $request->password = bcrypt($request->password);
                }
                
                $admin->save();
                return response()->json($this->structure(true, 'Sub Admin Updated Successfully!'), 200);
            } catch (\Throwable $th) {
                return response()->json($this->structure(false, "Internal server error!"), 200);
            }
        }
        return response()->json($this->structure(false, "Something went wrong, Try again!"), 200);
    }

    public function status(Request $request){

        if ($request->status == 'delete') {
           $data = ['deleted_at' => date('Y-m-d H:i:s')];
        } else {
          if ($request->status == 'enable') {
            $data = ['is_active'=>'Yes', 'updated_at' => date('Y-m-d H:i:s')];
          }
          else{
             $data = ['is_active'=>'No', 'updated_at' => date('Y-m-d H:i:s')];
          }
        }

        $response = Admin::where('role', 'sub-admin')->where('id', $request->id)->update($data);
        if ($response > 0) {
            if ($request->status == 'delete') {
              return response()->json($this->structure(true, "Deleted Succesfully."), 200);
            }
            return response()->json($this->structure(true, "Status Updated Succesfully"), 200);
        }
        return response()->json($this->structure(false, "Something went wrong, try again!"), 200);
    }
}
