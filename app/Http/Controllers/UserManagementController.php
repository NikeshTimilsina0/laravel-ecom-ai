<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->whereHas('role', function ($q) {
            $q->whereIn('role_name', ['super_admin', 'vendor', 'customer']);
        });

        if ($request->has('role') && $request->role !== '') {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('role_name', $request->role);
            });
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load('role', 'products', 'orders');
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($request->only('role_id'));

        return redirect()->route('admin.users.index')->with('success', 'User role updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function approveVendor(Request $request, User $user)
    {
        $user->load('role');
        
        if ($user->role->role_name !== 'vendor') {
            return redirect()->route('admin.users.index')->with('error', 'User is not a vendor.');
        }

        $user->update(['is_approved' => true]);

        return redirect()->route('admin.users.index')->with('success', 'Vendor approved successfully.');
    }

    public function rejectVendor(User $user)
    {
        $user->load('role');
        
        if ($user->role->role_name !== 'vendor') {
            return redirect()->route('admin.users.index')->with('error', 'User is not a vendor.');
        }

        $customerRole = Role::where('role_name', 'customer')->first();
        $user->update(['role_id' => $customerRole->id]);

        return redirect()->route('admin.users.index')->with('success', 'Vendor rejected and converted to customer.');
    }
}
