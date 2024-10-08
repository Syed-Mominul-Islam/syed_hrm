<?php

namespace App\Http\Controllers;

use App\Models\WorkResponsibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\Facades\DataTables;

class WorkResponsibilityController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = WorkResponsibility::
                with(['users'=>function($query){
                    $query->select('id','name');
                }])
                ->select('work_responsibilities.*')
                ->where('is_delete',0);
                return DataTables::of($data)
                ->addColumn('name', function($row) {
                    return $row->users->name;
                })
                ->addColumn('key_responsibilities', function($row) {
                        // Assuming `key_responsibilities` is a text or HTML field
                        return $row->key_responsibilities;
                    })
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-warning workresponsibilities-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger workresponsibilities-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions','name','key_responsibilities'])
                ->make(true);

        }
        // return $data;
        return view('cv.form');
    }
    public function store(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'responsibilities' => 'required|max:1024',
      ]);
      $responsibility = new WorkResponsibility();
      $responsibility->user_id = $request->user_id;
      $responsibility->responsibilities = $request->responsibilities;
      $responsibility->save();

      return response()->json([
        'success' => true,
         'message'=>'Work Responsibilities stored successfully'
      ]);
    }

    public function edit($id)
    {
        $workResponsibilities = WorkResponsibility::findOrFail($id);
        return response()->json($workResponsibilities);
    }
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'responsibilities' => 'required|max:1024',
        ]);
// In your update function
        Log::info($request->all()); // Log the request to check the user_id and other fields

        // Find the existing WorkResponsibility record by ID
        $responsibility = WorkResponsibility::findOrFail($id);

        // Update the fields with new data
        $responsibility->user_id = $request->user_id;
        $responsibility->responsibilities = $request->responsibilities;

        // Save the updated record
        $responsibility->save();

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Work Responsibilities updated successfully'
        ]);
    }
    public function destroy($id)
    {
        $responsibility = WorkResponsibility::findOrFail($id);
        // $employee->delete();
        $responsibility->is_delete = 1;
        $responsibility->update();

        return response()->json(['message' => 'Work Responsibilities deleted successfully.']);
    }

}
