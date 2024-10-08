<?php

namespace App\Http\Controllers;

use App\Models\EducationBackground;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EducationBackgroundController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EducationBackground::with(['users' => function($query) {
                $query->select('id', 'name'); // Only select 'id' and 'name'
            }])->select('education_background.*')->where('is_delete',0);
            return DataTables::of($data)
                ->addColumn('name', function($row) {
                    return $row->users->name;
                })
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-warning btn-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions','name'])
                ->make(true);
        }
        return view('education.index'); // Your Blade view for listing
    }
    public function store(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'degree' => 'required|string|max:255',
            'graduation_year' => 'required|date', // Use custom validation
            'major' => 'required|string|max:50',
            'university'=>'required|string|max:255'
        ]);
        $educationBackground=new EducationBackground();
        $educationBackground->user_id=$request->user_id;
        $educationBackground->degree=$request->degree;
        $educationBackground->university=$request->university;
        $educationBackground->graduation_year=$request->graduation_year;
        $educationBackground->major=$request->major;
        $educationBackground->save();
        return response()->json(['message'=>'Education Background Added Successfully'],200);
    }
    public function edit($id)
    {
        $educationBackground = EducationBackground::findOrFail($id);
        return response()->json($educationBackground);
    }

    // Method to update the education background record
    public function update(Request $request, $id)
    {
        $request->validate([
            'degree' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'graduation_year' => 'required|date',
            'major' => 'required|string|max:255',
        ]);

        $educationBackground = EducationBackground::findOrFail($id);
        
        // Update the record with new data
        $educationBackground->degree = $request->degree;
        $educationBackground->university = $request->university;
        $educationBackground->graduation_year = $request->graduation_year;
        $educationBackground->major = $request->major;
        
        $educationBackground->save();

        return response()->json(['message' => 'Record updated successfully']);
    }
    public function destroy($id){
        $educationBackground = EducationBackground::findOrFail($id);
        $educationBackground->is_delete=1;
        $educationBackground->save();
        return response()->json(['message' => 'Record deleted successfully']);

    }
}
