<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Applicant;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('applicant')->where('role', 'student')->get();
        return view('admin.user-management', compact('users'));
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Cegah hapus admin yang sedang login
            if ($user->id == auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri']);
            }
            
            // Hapus data aplikasi terkait
            if ($user->applicant) {
                $user->applicant->delete();
            }
            
            // Hapus user
            $user->delete();
            
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus user: ' . $e->getMessage()]);
        }
    }
}