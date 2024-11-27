<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
    public function create()
    {
        return view('data.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'files.*' => 'file|mimes:docx,pdf,png,jpg,jpeg|max:2048',
        ]);

        $data = Data::create([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads');

                $data->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('data.index')->with('status', 'Data created successfully.');
    }



    public function index(Request $request)
    {
        $query = $request->input('search');
        $data = Data::with('files') // Ensure related files are loaded
        ->when($query, function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->paginate(10); // Paginate results with 10 items per page

        return view('data.index', compact('data'));
    }




    public function destroy(Data $data)
    {
        // Loop through all associated files and delete them from the storage
        foreach ($data->files as $file) {
            if (\Storage::exists($file->file_path)) {
                \Storage::delete($file->file_path); // Delete the file from storage
            }
            $file->delete(); // Delete the file record from the database
        }

        // Delete the data record
        $data->delete();

        // Redirect with a success message
        return redirect()->route('data.index')->with('status', 'Data and its associated files deleted successfully.');
    }


    public function edit(Data $data)
    {
        return view('data.edit', compact('data'));
    }

    public function update(Request $request, Data $data)
    {
        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string|nullable',
            'files.*' => 'file|mimes:docx,pdf,png,jpg,jpeg|max:2048',
        ]);

        // Update the main data
        $data->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Process new file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads');

                $data->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        }

        // Handle file deletions
        if ($request->has('files_to_delete')) {
            foreach ($request->files_to_delete as $fileId) {
                $file = $data->files()->find($fileId);

                if ($file) {
                    // Delete file from storage
                    if (Storage::exists($file->file_path)) {
                        Storage::delete($file->file_path);
                    }

                    // Delete file record from database
                    $file->delete();
                }
            }
        }

        return redirect()->route('data.index')->with('status', 'Data updated successfully.');
    }



    public function saveData(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->savedData()->where('data_id', $id)->exists()) {
            $user->savedData()->attach($id);
            return redirect()->back()->with('success', 'Дані збережено.');
        }

        return redirect()->back()->with('info', 'Дані вже збережені.');
    }

    public function unsaveData(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->savedData()->where('data_id', $id)->exists()) {
            $user->savedData()->detach($id);
            return redirect()->back()->with('success', 'Дані видалено із збережених.');
        }

        return redirect()->back()->with('info', 'Дані не були збережені.');
    }

    public function viewSavedData(Request $request)
    {
        // Fetch paginated saved data for the authenticated user
        $query = auth()->user()->savedData();

        // Apply search filter if any
        if ($search = $request->input('search')) {
            $query = $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Paginate the results
        $savedData = $query->paginate(10);

        return view('data.saved', compact('savedData'));
    }



}
