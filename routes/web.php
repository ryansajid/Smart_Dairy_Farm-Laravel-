<?php

use App\Models\User;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ProfileController;

//for create role and permission :
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;






/*
|--------------------------------------------------------------------------
| Those use for role base redirection .
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    "This is the dashboard page for no roles";
    return view('dashboard'); // dummy view (never actually shown)
})->middleware(['auth', 'verified', 'role.redirect'])
  ->name('dashboard');




/*
|--------------------------------------------------------------------------
| Role-wise Dashboards
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/animal/store', [AnimalController::class, 'store'])->name('admin.animal.store');
    Route::get('/admin/animal/milk-production', [AnimalController::class, 'getMilkProduction'])->name('admin.animal.milk-production');
    Route::get('/admin/role/create', [AdminController::class, 'createRole'])->name('admin.role.create');
    Route::post('/admin/role/store', [AdminController::class, 'storeRole'])->name('admin.role.store');
    Route::get('/admin/role/assign/create', [AdminController::class, 'createAssignRole'])->name('admin.role.assign.create');
    Route::post('/admin/role/assign/store', [AdminController::class, 'storeAssignRole'])->name('admin.role.assign.store');
    Route::post('/admin/role/assign/remove', [AdminController::class, 'removeUserRole'])->name('admin.role.assign.remove');
    Route::get('/admin/visitor/registration/create', [AdminController::class, 'createVisitorRegistration'])->name('admin.visitor.registration.create');
    Route::post('/admin/visitor/registration/store', [AdminController::class, 'storeVisitorRegistration'])->name('admin.visitor.registration.store');
    Route::get('/admin/visitor/registration/search-host', [AdminController::class, 'searchHost'])->name('admin.visitor.registration.search-host');
    Route::get('/admin/visitor/list', [AdminController::class, 'visitorList'])->name('admin.visitor.list');
    Route::get('/admin/visitor/{id}/edit', [AdminController::class, 'editVisitor'])->name('admin.visitor.edit');
    Route::post('/admin/visitor/{id}/update', [AdminController::class, 'updateVisitor'])->name('admin.visitor.update');
    Route::delete('/admin/visitor/{id}/delete', [AdminController::class, 'deleteVisitor'])->name('admin.visitor.delete');
});

Route::middleware(['auth', 'role:receptionist'])->group(function () {
    // dd("This is the dashboard page for receptionist");
    Route::get('/receptionist/dashboard', function () {
        return "This is the dashboard page for receptionist";
    })->name('receptionist.dashboard');
    // Route::get('/receptionist/dashboard', fn () => view('receptionist.dashboard'))
    //     ->name('receptionist.dashboard');
});

Route::middleware(['auth', 'role:visitor'])->group(function () {
    //  dd("This is the dashboard page for visitor");
    Route::get('/visitor/dashboard', function () {
        return "This is the dashboard page for visitor";
    })->name('visitor.dashboard');
    // Route::get('/visitor/dashboard', fn () => view('visitor.dashboard'))
    //     ->name('visitor.dashboard');
});

/*
|--------------------------------------------------------------------------
| Fallback (any other role → staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    //  dd("This is the dashboard page for staff");
    Route::get('/staff/dashboard', function () {
        return "This is the dashboard page for staff";
    })->name('staff.dashboard');
    // Route::get('/staff/dashboard', fn () => view('staff.dashboard'))
    //     ->name('staff.dashboard');
});



/*
|--------------------------------------------------------------------------
| Guest pages (guest only)
|--------------------------------------------------------------------------
*/


Route::get('/', function(){


// Role::create(['name' => 'admin']);
// Role::create(['name' => 'staff']);
// Role::create(['name' => 'receptionist']);
// Role::create(['name' => 'visitor']);

// dd(Role::all());
//---------- add role to any user --------------------------
    // $user = User::where('name','Staff')->first();
    // // dd($user);
    // $user->assignRole('staff');
    // dd($user->getRoleNames());
    // $user->removeRole('staff');

    // dd($user->getRoleNames());

//---------- add permission to any user ------------------------
// use Spatie\Permission\Models\Permission;
    // $user = User::latest()->first();
    // $user->givePermissionTo('create users');

    return "This is the Homepage ";
    return view('home');
})->name('home');



/*
|--------------------------------------------------------------------------
| Auth pages (guest only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);


});



/*
|--------------------------------------------------------------------------
| Password Reset (ALLOW AUTH + GUEST)
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [PasswordResetController::class, 'request'])
    ->name('password.request');

Route::post('/forgot-password-email', [PasswordResetController::class, 'email'])
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'update'])
    ->name('password.update');

// Send reset email to currently authenticated user
Route::post('/profile/send-reset-email', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink($request->only('email'));

    return back()->with('status', $status === Password::RESET_LINK_SENT
        ? __($status)
        : __('Failed to send reset link. ' . __($status)));
})->name('profile.send-reset-email');


/*
|--------------------------------------------------------------------------
| Authenticated pages
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Test email route
Route::get('/test-mail', function() {
    try {
        Mail::raw('This is a test email from Laravel', function($message) {
            $message->to('ashrafulunisoft@gmail.com')
                    ->subject('Test Email');
        });
        return 'Email sent successfully! Check your inbox.';
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});


//---------------------------------------------------------------------------
//for logout :


Route::get('/test', function () {
    return "Test File";
    return redirect('/login');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});




// -------------------------------------------------------------------------
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
