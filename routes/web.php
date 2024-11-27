<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/data');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Models\User;

use App\Http\Middleware\CheckRole;

use Illuminate\Http\Request;

Route::middleware([CheckRole::class . ':superadmin'])->group(function () {
    // User management search and list route
    Route::get('/user-management', function (Request $request) {
        $query = $request->input('search');

        $users = User::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%');
        })->paginate(10);

        return view('user-management', compact('users'));
    })->name('user-management');

    // Promote user to Admin
    Route::put('/users/{user}/make-admin', function (User $user) {
        $user->update(['role' => 'admin']);
        return redirect()->back()->with('status', 'User promoted to Admin.');
    })->name('users.make-admin');

    // Revert user to Default role
    Route::put('/users/{user}/remove-admin', function (User $user) {
        $user->update(['role' => 'default']);
        return redirect()->back()->with('status', 'User reverted to Default.');
    })->name('users.remove-admin');

    // Promote user to Superadmin
    Route::put('/users/{user}/make-superadmin', function (User $user) {
        $user->update(['role' => 'superadmin']); // Promote the target user

        // Update current user to admin and reauthenticate
        $currentUser = Auth::user();
        $currentUser->update(['role' => 'admin']);
        Auth::login($currentUser); // Re-login the user to refresh the session

        return redirect('/data')->with('status', 'User promoted to Superadmin.');
    })->name('users.make-superadmin');
});


use App\Http\Controllers\DataController;
// Data routes
Route::get('/data', [DataController::class, 'index'])->name('data.index'); // List all data

Route::middleware(['auth', CheckRole::class . ':superadmin|admin'])->group(function () {
    Route::get('/data/create', [DataController::class, 'create'])->name('data.create'); // Show form for creating data
    Route::post('/data', [DataController::class, 'store'])->name('data.store'); // Store new data
    Route::get('/data/{data}/edit', [DataController::class, 'edit'])->name('data.edit'); // Edit a data item
    Route::put('/data/{data}', [DataController::class, 'update'])->name('data.update'); // Update a data item
    Route::delete('/data/{data}', [DataController::class, 'destroy'])->name('data.destroy'); // Delete a data item
});


use Illuminate\Support\Facades\Storage;
use App\Models\File;

Route::get('/file-download/{id}', function ($id, Request $request) {
    $filePath = $request->query('file_path');
    $filename = $request->query('filename');

    if ($filePath && Storage::exists($filePath)) {
        return Storage::download($filePath, $filename);
    }

    return response()->json(['error' => 'File not found!'], 404);
})->name('file.download');
Route::middleware('auth')->group(function () {
    Route::post('/data/{id}/save', [DataController::class, 'saveData'])->name('data.save');
    Route::delete('/data/{id}/unsave', [DataController::class, 'unsaveData'])->name('data.unsave');

// Route for viewing saved data
    Route::get('/saved-data', [DataController::class, 'viewSavedData'])->name('data.saved');
});






//Route::middleware([CheckRole::class.':admin'])->group(function () {
//    Route::get('/admin-page', function () {
//        return view('admin-page');
//    });
//});



