<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list(Request $req)
    {
        $perPage = max(1,min((int)$req->integer('per_page',12),100));
        $q = (string)$req->get('q','');

        $query = User::query()
            ->when($q, fn($qr)=> $qr->where('name','like',"%$q%")
                                    ->orWhere('email','like',"%$q%"))
            ->orderByDesc('id');

        $page = $query->paginate($perPage);

        return response()->json([
            'ok'=>true,
            'data'=>$page->items(),
            'meta'=>[
                'current_page'=>$page->currentPage(),
                'last_page'=>$page->lastPage(),
                'per_page'=>$page->perPage(),
                'total'=>$page->total(),
            ]
        ]);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password'=>['required','string','min:6'],
            'role'  => ['required', Rule::in(['admin','user'])],
        ]);
        $data['password'] = Hash::make($data['password']);
        $u = User::create($data);
        return response()->json(['ok'=>true,'message'=>'Tạo người dùng thành công','data'=>$u]);
    }

    public function update(Request $req, User $user)
    {
        $data = $req->validate([
            'name'  => ['sometimes','required','string','max:255'],
            'email' => ['sometimes','required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password'=>['nullable','string','min:6'],
            'role'  => ['sometimes', Rule::in(['admin','user'])],
        ]);
        if(!empty($data['password'])) $data['password'] = Hash::make($data['password']); else unset($data['password']);
        $user->update($data);
        return response()->json(['ok'=>true,'message'=>'Cập nhật người dùng thành công']);
    }

    public function destroy(User $user)
    {
        if(auth()->id() === $user->id){
            return response()->json(['ok'=>false,'message'=>'Không thể xoá chính bạn'], 422);
        }
        $user->delete();
        return response()->json(['ok'=>true,'message'=>'Đã xoá người dùng']);
    }
}
