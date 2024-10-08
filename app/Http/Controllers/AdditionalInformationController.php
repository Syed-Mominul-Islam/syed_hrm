<?php

namespace App\Http\Controllers;

use App\Models\AdditionalInformation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdditionalInformationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AdditionalInformation::with(['users' => function ($query) {
                $query->select('id', 'name'); // Only select 'id' and 'name' of the user
            }])
                ->select('additional_information.*') // Ensure you are selecting the correct table
                ->where('is_delete', 0); // Filter out deleted entries

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->users->name; // Display user name through relationship
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-warning additional_info-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger additional_info-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['actions', 'name'])
                ->make(true);
        }

        return view('cv.form'); // Your Blade view for listing additional information
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'languages_known' => 'nullable|array',
            'languages_known.*' => 'string|max:255',
            'hobbies' => 'nullable|array',
            'hobbies.*' => 'string|max:255',
            'volunteer_work' => 'nullable|array',
            'volunteer_work.*' => 'string|max:255',
        ]);

        // Check if languages_known, hobbies, and volunteer_work arrays are provided
//        $allEntries = []; // To hold all entries before saving to the database

        foreach ($request->languages_known as $index => $language) {
            AdditionalInformation::insert([
               'user_id'=>$request->user_id,
               'languages_known'=>$language,
               'hobbies'=>$request->hobbies[$index],
               'volunteer_work'=>$request->volunteer_work[$index],
            ]);
        }

        // Insert all entries in a single query

        return response()->json(['success' => true, 'message' => 'Additional information added successfully.']);
    }


    public function edit($id)
    {
        // Fetch additional information by ID
        $additionalInfo = AdditionalInformation::findOrFail($id);
        return response()->json($additionalInfo);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validate user_id
            'languages_known' => 'nullable|string|max:255', // Languages Known
            'hobbies' => 'nullable|string|max:255', // Hobbies
            'volunteer_work' => 'nullable|string|max:255', // Volunteer Work
        ]);

        // Find the additional information record
        $additionalInfo = AdditionalInformation::findOrFail($id);

        // Update the record
        $additionalInfo->update([
            'user_id' => $request->user_id,
            'languages_known' => $request->languages_known,
            'hobbies' => $request->hobbies,
            'volunteer_work' => $request->volunteer_work,
        ]);

        return response()->json(['success' => 'Additional information updated successfully.']);
    }

    public function destroy($id)
    {
        // Find the additional information
        $additionalInfo = AdditionalInformation::find($id);

        if ($additionalInfo) {
            // Soft delete the record by setting `is_delete` flag to 1
            $additionalInfo->update([
                'is_delete' => 1,
            ]);
            return response()->json(['success' => 'Additional information deleted successfully.']);
        }

        return response()->json(['error' => 'Additional information not found.'], 404);
    }
}
