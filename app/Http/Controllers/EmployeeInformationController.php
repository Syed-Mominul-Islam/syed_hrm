<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeInformationController extends Controller
{
    public function index(Request $request){
        $users=User::get();
        if ($request->ajax()) {
            $data = EmployeeInformation::where('is_delete',0)->select(['id', 'user_id','name', 'date_of_birth', 'address', 'contact_number', 'email', 'nationality', 'marital_status']);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-warning edit" data-id="' . $row->id . '">Edit</button> ' .
                           '<button class="btn btn-danger delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->make(true);
        }
        return view('cv.index',compact('users'));
    }
    public function store(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'contact_number' => 'required', // Use custom validation
            'email' => 'required|email|max:255',
            'nationality' => 'required|string|max:50',
            'marital_status' => 'required|string|max:50',
        ]);
        // dd($validated);
        $username=User::where('id',$request->user_id)->first();
        $employeeInfo=new EmployeeInformation();
        $employeeInfo->user_id=$request->user_id;
        $employeeInfo->name=$username->name;
        $employeeInfo->date_of_birth=$request->date_of_birth;
        $employeeInfo->address=$request->address;
        $employeeInfo->contact_number=$request->contact_number;
        $employeeInfo->email=$request->email;
        $employeeInfo->nationality=$request->nationality;
        $employeeInfo->marital_status=$request->marital_status;
        $employeeInfo->save();
        return response()->json([
            'message'=>'Persoanl Information stored successfully'
        ]);
    }
    public function edit($id)
    {
        $employee = EmployeeInformation::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            // 'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'nationality' => 'required|string|max:50',
            'marital_status' => 'required|string|max:50',
        ]);
        $username=User::where('id',$request->user_id)->first();
      
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $employeeInfo->user_id=$request->user_id;
        $employeeInfo->name=$username->name;
        $employeeInfo->date_of_birth=$request->date_of_birth;
        $employeeInfo->address=$request->address;
        $employeeInfo->contact_number=$request->contact_number;
        $employeeInfo->email=$request->email;
        $employeeInfo->nationality=$request->nationality;
        $employeeInfo->marital_status=$request->marital_status;
        $employeeInfo->save();
        
        // $employee->update($validated);

        return response()->json(['message' => 'Employee information updated successfully.']);
    }

    public function destroy($id)
    {
        $employee = EmployeeInformation::findOrFail($id);
        // $employee->delete();
        $employee->is_delete = 1;
        $employee->update();

        return response()->json(['message' => 'Employee information deleted successfully.']);
    }
}
