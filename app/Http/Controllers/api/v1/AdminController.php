<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Lấy danh sách tất cả admin (cần permission: view_admins)
     */
    public function index()
    {
        $admins = Admin::with('roles', 'permissions')->get();
        
        return response()->json([
            'status' => true,
            'data' => $admins
        ]);
    }

    /**
     * Tạo admin mới (cần permission: create_admins)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8',
        ]);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tạo admin thành công',
            'data' => $admin
        ], 201);
    }

    /**
     * Cập nhật admin (cần permission: edit_admins)
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:admins,email,' . $id,
        ]);

        $admin->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật admin thành công',
            'data' => $admin
        ]);
    }

    /**
     * Xóa admin (cần permission: delete_admins)
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa admin thành công'
        ]);
    }

    /**
     * Kiểm tra quyền của user hiện tại
     */
    public function checkPermissions(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'status' => true,
            'data' => [
                'user' => $user,
                'roles' => $user->roles,
                'permissions' => $user->getAllPermissions(),
                'can_view_admins' => $user->can('view_admins'),
                'can_create_admins' => $user->can('create_admins'),
                'can_edit_admins' => $user->can('edit_admins'),
                'can_delete_admins' => $user->can('delete_admins'),
            ]
        ]);
    }
}