<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalSkill;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProfessionalSkillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProfessionalSkill::with(['users' => function($query) {
                $query->select('id', 'name'); // Only select 'id' and 'name'
            }])->select('professional_skills.*')->where('is_delete',0);
            return DataTables::of($data)
                ->addColumn('name', function($row) {
                    return $row->users->name;
                })
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-warning professional_skills-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger professional_skills-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions','name'])
                ->make(true);
        }
        return view('cv.form'); // Your Blade view for listing
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'skill_name.*' => 'required|string|max:255',
            'description.*' => 'required|string',
        ]);

        foreach ($request->skill_name as $index => $skill_name) {
            ProfessionalSkill::create([
                'user_id' => $request->user_id,
                'skill_name' => $skill_name,
                'description' => $request->description[$index] ?? null,
            ]);
        }

//        if ($request->ajax()) {
            return response()->json(['success' => true,'message' => 'Professional skill added successfully.']);
//        }

//        return redirect()->back()->with('success', 'Skills added successfully!');
    }
    public function edit($id)
    {
        $professionalSkill=ProfessionalSkill::findOrFail($id);
        return response()->json($professionalSkill);
    }
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user_id exists
            'skill_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Find the professional skill record
        $professionalSkill = ProfessionalSkill::findOrFail($id);

        // Update the record
        $professionalSkill->update([
            'user_id' => $request->user_id,
            'skill_name' => $request->skill_name,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Professional skill updated successfully.']);
    }
    public function destroy($id)
    {
        $skill = ProfessionalSkill::find($id);

        if ($skill) {
            $skill->update([
                'is_delete' => 1,
            ]);
            return response()->json(['success' => 'Skill deleted successfully.']);
        }

        return response()->json(['error' => 'Skill not found.'], 404);
    }

}
