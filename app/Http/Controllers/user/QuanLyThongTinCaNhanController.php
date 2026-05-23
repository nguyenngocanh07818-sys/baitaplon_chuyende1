<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class QuanLyThongTinCaNhanController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('user.account', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update($data);

        return response()->json(['ok' => true, 'message' => 'Cập nhật tên thành công']);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mật khẩu hiện tại không đúng.',
            ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(['ok' => true, 'message' => 'Đổi mật khẩu thành công']);
    }
}