<?php

namespace App\Http\Controllers;

use App\Models\InterpersonalSkill;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InterpersonalSkillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InterpersonalSkill::with(['users' => function ($query) {
                $query->select('id', 'name'); // Only select 'id' and 'name'
            }])
                ->select('interpersonal_skills.*') // Ensure you are selecting the correct table
                ->where('is_delete', 0);

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->users->name; // Use the correct relationship
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-warning interpersonal_skills-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger interpersonal_skills-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions', 'name'])
                ->make(true);
        }
        return view('cv.form'); // Your Blade view for listing
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'skill_name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string', // Description can be optional
        ]);

        foreach ($request->skill_name as $index => $skill_name) {
            InterpersonalSkill::create([
                'user_id' => $request->user_id,
                'skill_name' => $skill_name,
                'description' => $request->description[$index] ?? null,
                'is_delete' => 0, // Set default value for is_delete
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Interpersonal skill added successfully.']);
    }

    public function edit($id)
    {
        $interpersonalSkill = InterpersonalSkill::findOrFail($id);
        return response()->json($interpersonalSkill);
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user_id exists
            'skill_name' => 'required|string|max:255',
            'description' => 'nullable|string', // Description can be optional
        ]);

        // Find the interpersonal skill record
        $interpersonalSkill = InterpersonalSkill::findOrFail($id);

        // Update the record
        $interpersonalSkill->update([
            'user_id' => $request->user_id,
            'skill_name' => $request->skill_name,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Interpersonal skill updated successfully.']);
    }

    public function destroy($id)
    {
        $skill = InterpersonalSkill::find($id);

        if ($skill) {
            $skill->update([
                'is_delete' => 1,
            ]);
            return response()->json(['success' => 'Skill deleted successfully.']);
        }

        return response()->json(['error' => 'Skill not found.'], 404);
    }
}
