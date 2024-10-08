<?php

namespace App\Http\Controllers;

use App\Models\LearningInterest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LearningInterestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LearningInterest::with(['users' => function ($query) {
                $query->select('id', 'name'); // Only select 'id' and 'name' of the user
            }])
                ->select('learning_interests.*') // Ensure you are selecting the correct table
                ->where('is_delete', 0); // Filter out deleted entries

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->users->name; // Display user name through relationship
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-warning learning_interest-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger learning_interest-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions', 'name'])
                ->make(true);
        }

        return view('cv.form'); // Your Blade view for listing learning interests
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'interest.*' => 'required|string|max:255', // Interest is mandatory
        ]);

        // Save each interest in the database
        foreach ($request->interest as $index => $interest) {
            LearningInterest::create([
                'user_id' => $request->user_id,
                'interest' => $interest,
                'completed_course' => $request->completed_course[$index],
                'is_delete' => 0, // Default value for is_delete
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Learning interest added successfully.']);
    }

    public function edit($id)
    {
        // Fetch learning interest by ID
        $learningInterest = LearningInterest::findOrFail($id);
        return response()->json($learningInterest);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validate user_id
            'interest' => 'required|string|max:255', // Interest is mandatory
        ]);

        // Find the learning interest record
        $learningInterest = LearningInterest::findOrFail($id);

        // Update the record
        $learningInterest->update([
            'user_id' => $request->user_id,
            'interest' => $request->interest,
            'completed_course' => $request->completed_course,

        ]);

        return response()->json(['success' => 'Learning interest updated successfully.']);
    }

    public function destroy($id)
    {
        // Find the learning interest
        $learningInterest = LearningInterest::find($id);

        if ($learningInterest) {
            // Soft delete the record by setting `is_delete` flag to 1
            $learningInterest->update([
                'is_delete' => 1,
            ]);
            return response()->json(['success' => 'Learning interest deleted successfully.']);
        }

        return response()->json(['error' => 'Learning interest not found.'], 404);
    }
}
