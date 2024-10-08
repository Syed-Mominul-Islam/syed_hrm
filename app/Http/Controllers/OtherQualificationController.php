<?php

namespace App\Http\Controllers;

use App\Models\OtherQualification;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\Facades\DataTables;

class OtherQualificationController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = OtherQualification::
                with(['users'=>function($query){
                    $query->select('id','name');
                }])
                ->select('other_qualification.*')
                ->where('is_delete',0);
                return DataTables::of($data)
                ->addColumn('name', function($row) {
                    return $row->users->name;
                })
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-warning otherQalification-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger otherQalification-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions','name'])
                ->make(true);
            
        }
        return view('cv.form');
    }
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'qualification_name' => 'required|array',
            'qualification_name.*' => 'required|string|max:255',
            'passing_year' => 'required|array',
            'passing_year.*' => 'required|date',
        ]);

        // Loop through qualifications and store them
        foreach ($request->qualification_name as $index => $qualificationName) {
            OtherQualification::create([
                'user_id' => $request->user_id,
                'qualification_name' => $qualificationName,
                'passing_year' => $request->passing_year[$index],
            ]);
        }

        return response()->json(['message' => 'Qualifications saved successfully!']);
    }
    public function edit($id)
    {
        $otherQualification = OtherQualification::findOrFail($id);
        return response()->json($otherQualification);
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'qualification_name' => 'required|string|max:255',
            'passing_year' => 'required|date',
        ]);

        // Find the qualification record by ID
        $qualification = OtherQualification::find($id);
        if (!$qualification) {
            return response()->json(['success' => false, 'message' => 'Qualification not found'], 404);
        }

        // Update the qualification
        $qualification->user_id = $request->user_id;
        $qualification->qualification_name = $request->qualification_name;
        $qualification->passing_year = $request->passing_year;
        $qualification->save();

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Other Qualification updated successfully']);
    }
    public function destroy($id){
        $otherQualification=OtherQualification::findOrFail($id);
        $otherQualification->is_delete=1;
        $otherQualification->save();
        return response()->json(['message' => 'Record deleted successfully']);

    }


}
