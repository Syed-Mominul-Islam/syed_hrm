<?php

namespace App\Http\Controllers;

use App\Models\NotableProject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotableProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = NotableProject::with(['users' => function ($query) {
                $query->select('id', 'name'); // Only select 'id' and 'name'
            }])
                ->select('notable_projects.*') // Ensure you are selecting the correct table
                ->where('is_delete', 0);

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->users->name; // Use the correct relationship
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-warning notable_project-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger notable_project-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions', 'name'])
                ->make(true);
        }
        return view('cv.notable_projects'); // Your Blade view for listing notable projects
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'notable_project_name.*' => 'required|string|max:255',
            'notable_project_description.*' => 'required|string', // Description can be optional
        ]);

        foreach ($request->notable_project_name as $index => $project_name) {
            NotableProject::create([
                'user_id' => $request->user_id,
                'notable_project_name' => $project_name,
                'notable_project_description' => $request->notable_project_description[$index] ?? null,
                'is_deleted' => 0, // Set default value for is_deleted
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Notable project added successfully.']);
    }

    public function edit($id)
    {
        $notableProject = NotableProject::findOrFail($id);
        return response()->json($notableProject);
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user_id exists
            'notable_project_name' => 'required|string|max:255',
            'notable_project_description' => 'nullable|string', // Description can be optional
        ]);

        // Find the notable project record
        $notableProject = NotableProject::findOrFail($id);

        // Update the record
        $notableProject->update([
            'user_id' => $request->user_id,
            'notable_project_name' => $request->notable_project_name,
            'notable_project_description' => $request->notable_project_description,
        ]);

        return response()->json(['success' => 'Notable project updated successfully.']);
    }

    public function destroy($id)
    {
        $notableProject = NotableProject::find($id);

        if ($notableProject) {
            $notableProject->update([
                'is_delete' => 1,
            ]);
            return response()->json(['success' => 'Notable project deleted successfully.']);
        }

        return response()->json(['error' => 'Notable project not found.'], 404);
    }
}
