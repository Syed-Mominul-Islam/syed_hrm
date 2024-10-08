<?php

namespace App\Http\Controllers;

use App\Models\JobExperience;
use App\Models\WorkResponsibility;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JobExperienceController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = JobExperience::
            with(['users'=>function($query){
                $query->select('id','name');
            }])
                ->select('job_experiences.*')
                ->where('is_delete',0);
            return DataTables::of($data)
                ->addColumn('name', function($row) {
                    return $row->users->name;
                })
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-warning job_experiences-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger job_experiences-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions','name'])
                ->make(true);

        }
        // return $data;
        return view('cv.form');
    }
    public function store(Request $request){
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user exists
            'company_name.*' => 'required|string|max:255',
            'designation.*' => 'required|string|max:255',
            'date.*' => 'required|date',
            'key_responsibilities.*' => 'required|string',
        ]);

        // Loop through the validated data and create job experience records
        foreach ($validatedData['company_name'] as $index => $companyName) {
            // Create a new JobExperience record
            $jobExp = new JobExperience();
            $jobExp->user_id = $validatedData['user_id'];
            $jobExp->company_name = $companyName;
            $jobExp->designation = $validatedData['designation'][$index]; // Get corresponding designation
            $jobExp->date = $validatedData['date'][$index]; // Get corresponding date
            $jobExp->key_responsibilities = $validatedData['key_responsibilities'][$index]; // Get corresponding key responsibilities
            $jobExp->save(); // Save the job experience record
        }
        return response()->json(['success'=>true,'message' => 'Job experience added successfully']);
    }
    public  function edit($id){
        $jobExp=JobExperience::findOrFail($id);
        return response()->json($jobExp);
    }
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'date' => 'required|date',
            'key_responsibilities' => 'required|string',
        ]);

        // Find the job experience entry by ID
        $responsibility = JobExperience::findOrFail($id);

        // Update the fields
        $responsibility->user_id = $validatedData['user_id'];
        $responsibility->company_name = $validatedData['company_name'];
        $responsibility->designation = $validatedData['designation'];
        $responsibility->date = $validatedData['date'];
        $responsibility->key_responsibilities = $validatedData['key_responsibilities'];
        $responsibility->save(); // Save changes to the database

        return response()->json(['message' => 'Job experience updated successfully.']);
    }

    public function destroy($id){
        $jobExp=JobExperience::findOrFail($id);
        $jobExp->is_delete=1;
        $jobExp->update();

        return response()->json(['message' => 'Job Experience deleted successfully.']);
    }
}
//
