<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;


class SuperAdminController extends Controller
{
    public function showLoginForm()
    {
        return view('super_admin.super-admin-login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $superAdmin = SuperAdmin::where('email', $request->email)->first();
    
        if (!$superAdmin) {
            return back()->with('error', 'Invalid email address!');
        }
        
        if ($request->password !== $superAdmin->password) {   // <-- Plain text check
            return back()->with('error', 'Invalid password!');
        }
    
    
        Auth::guard('super_admin')->login($superAdmin);  // Optional if you want to keep guard-based login
    
        return redirect()->route('superadmin.dashboard')->with('success', 'Login successful!');
    }
    
    public function dashboard()
    {
        if (!Auth::guard('super_admin')->check()) {
            return redirect()->route('superadmin.login')->with('error', 'Please login first.');
        }

        $superAdmin = Auth::guard('super_admin')->user();

        return view('super_admin.super-admin-dashboard', compact('superAdmin'));
    }

    public function addAdmin()
    {
        if (!Auth::guard('super_admin')->check()) {
            return redirect()->route('superadmin.login')->with('error', 'Please login first.');
        }

        return view('super_admin.add-admin');
    }

    public function saveAdminRegister(Request $request)
    {
        try {
            // Save data to `admins` table
            $admin = new Admin();
            $admin->admin_id = 'ADMIN' . rand(1000, 9999);  // Or any other logic for admin_id
            $admin->first_name = $request->first_name;
            $admin->last_name = $request->last_name;
            $admin->mobile_no = $request->phonenumber;

            if ($request->hasFile('photo')) {
                $adminPhoto = $request->file('photo');
                $imageName = time() . '.' . $adminPhoto->getClientOriginalExtension();
                $adminPhoto->move(public_path('uploads/admin_photo'), $imageName);
                $admin->photo = 'uploads/admin_photo/' . $imageName;
            }

            $admin->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Admin registered successfully!');

        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', 'Failed to register admin. Error: ' . $e->getMessage());
        }
    }

    public function manageAdmin()
    {
        $admins = Admin::where('status', '!=', 'deleted')->get();

        return view('super_admin.manage-admin', compact('admins'));

    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
    
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->mobile_no = $request->mobile_no;
    
        if ($request->hasFile('photo')) {
            $adminPhoto = $request->file('photo');
            $imageName = time() . '.' . $adminPhoto->getClientOriginalExtension();
            $adminPhoto->move(public_path('uploads/admin_photo'), $imageName);
            $admin->photo = 'uploads/admin_photo/' . $imageName;
        }
    
        $admin->save();
    
        return response()->json(['message' => 'Admin updated successfully']);
    }

    public function softDelete($id)
    {
        \Log::info('Soft delete called for admin ID: ' . $id);
    
        $admin = Admin::findOrFail($id);
        $admin->status = 'deleted';
        $admin->save();
    
        return response()->json(['success' => true, 'message' => 'Admin marked as deleted']);
    }
    


}
