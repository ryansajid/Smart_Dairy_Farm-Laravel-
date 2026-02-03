<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visitor;
use App\Models\Visit;
use App\Models\VisitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function dashboard(){
        return view('vms.backend.admin.admin_dashboard');
    }

    public function createRole(){
        return view('vms.backend.admin.Addrole');
    }

    public function storeRole(Request $request){
        $request->validate([
            'role_name' => 'required|string|unique:roles,name|max:255',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,restricted',
        ]);

        $role = Role::create([
            'name' => $request->role_name,
        ]);

        // If permissions are selected, sync them to the role
        if ($request->has('permissions')) {
            $permissions = [];

            if (in_array('dashboard', $request->permissions)) {
                $permissions[] = Permission::firstOrCreate(['name' => 'view dashboard']);
            }
            if (in_array('users', $request->permissions)) {
                $permissions[] = Permission::firstOrCreate(['name' => 'manage users']);
            }
            if (in_array('roles', $request->permissions)) {
                $permissions[] = Permission::firstOrCreate(['name' => 'manage roles']);
            }
            if (in_array('reports', $request->permissions)) {
                $permissions[] = Permission::firstOrCreate(['name' => 'view reports']);
            }
            if (in_array('audit', $request->permissions)) {
                $permissions[] = Permission::firstOrCreate(['name' => 'view audit logs']);
            }
            if (in_array('settings', $request->permissions)) {
                $permissions[] = Permission::firstOrCreate(['name' => 'manage settings']);
            }

            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.role.create')
            ->with('success', 'Role "' . $request->role_name . '" created successfully!');
    }

    public function createAssignRole(){
        $users = User::all();
        $roles = Role::all();
        return view('vms.backend.admin.Assignrole', compact('users', 'roles'));
    }

    public function storeAssignRole(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:active,pending,restricted',
            'remarks' => 'nullable|string|max:500',
        ]);

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        // Remove existing roles and assign new one
        $user->syncRoles([$role->id]);

        // Log the assignment (optional - you might want to create a role_assignments table)
        // For now, we'll just return success

        return redirect()->route('admin.role.assign.create')
            ->with('success', 'Role "' . $role->name . '" assigned to ' . $user->name . ' successfully!');
    }

    public function removeUserRole(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        $roles = $user->getRoleNames();
        $user->removeRole($roles->first());

        return redirect()->route('admin.role.assign.create')
            ->with('success', 'Role removed from ' . $user->name . ' successfully!');
    }

    public function createVisitorRegistration(){
        $users = User::all();
        $visitTypes = VisitType::all();
        return view('vms.backend.admin.VisitorRegistration', compact('users', 'visitTypes'));
    }

    public function storeVisitorRegistration(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:visitors,email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'host_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:500',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_type_id' => 'required|exists:visit_types,id',
            'face_image' => 'nullable|string',
        ]);

        // Create or find visitor
        $visitor = Visitor::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->company,
                'is_blocked' => false,
            ]
        );

        // Find or create host user by name
        $hostUser = User::where('name', 'like', '%' . $request->host_name . '%')->first();

        if (!$hostUser) {
            // If host doesn't exist, use current admin as default host
            $hostUser = Auth::user();
        }

        // Create visit record
        $visit = Visit::create([
            'visitor_id' => $visitor->id,
            'meeting_user_id' => $hostUser->id,
            'visit_type_id' => $request->visit_type_id,
            'purpose' => $request->purpose,
            'schedule_time' => $request->visit_date,
            'status' => 'approved', // Auto-approve when created by admin
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.visitor.registration.create')
            ->with('success', 'Visitor ' . $visitor->name . ' registered successfully!')
            ->withInput();
    }

    public function searchHost(Request $request)
    {
        $query = $request->get('q');
        $users = User::where('name', 'like', '%' . $query . '%')
                    ->limit(10)
                    ->get(['id', 'name']);

        return response()->json($users);
    }

    public function visitorList()
    {
        $visitors = Visit::with(['visitor', 'type', 'meetingUser'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('vms.backend.admin.visitor-list', compact('visitors'));
    }

    public function editVisitor($id)
    {
        $visit = Visit::with(['visitor', 'type', 'meetingUser'])->findOrFail($id);
        $users = User::all();
        $visitTypes = VisitType::all();

        return view('vms.backend.admin.edit-visitor', compact('visit', 'users', 'visitTypes'));
    }

    public function updateVisitor(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'host_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:500',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_type_id' => 'required|exists:visit_types,id',
            'status' => 'required|in:approved,pending,completed,cancelled',
        ]);

        try {
            $visit = Visit::findOrFail($id);

            // Update visitor information
            $visitor = Visitor::findOrFail($visit->visitor_id);
            $visitor->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->company,
            ]);

            // Find host user
            $hostUser = User::where('name', 'like', '%' . $request->host_name . '%')->first();
            if (!$hostUser) {
                $hostUser = Auth::user();
            }

            // Update visit
            $visit->update([
                'meeting_user_id' => $hostUser->id,
                'visit_type_id' => $request->visit_type_id,
                'purpose' => $request->purpose,
                'schedule_time' => $request->visit_date,
                'status' => $request->status,
                'approved_at' => $request->status === 'approved' ? now() : $visit->approved_at,
            ]);

            // Check if request expects JSON (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Visit updated successfully!'
                ]);
            }

            return redirect()->route('admin.visitor.list')
                ->with('success', 'Visit updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteVisitor($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Visit deleted successfully!'
        ]);
    }
}
