<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /** ============ Helpers cho OTP ở bước ĐĂNG KÝ ============ */
    protected function generateOtp(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function putRegisterOtpToSession(string $email, string $otp): void
    {
        session([
            'register.email'      => $email,
            'register.code'       => $otp,
            'register.expires_at' => now()->addMinutes(5)->timestamp, // hết hạn 5'
            'register.last_sent'  => now()->timestamp,                // phục vụ cooldown 30s
        ]);
    }

    protected function canSendRegisterCode(): array
    {
        $last = session('register.last_sent');
        if (!$last) return [true, 0];
        $elapsed = now()->timestamp - (int)$last;
        $remain  = 30 - $elapsed;
        return [$elapsed >= 30, max(0, $remain)];
    }

    protected function sendOtpMail(string $toEmail, string $otp): void
    {
        Mail::raw("Mã xác nhận đăng ký của bạn: {$otp}\nHiệu lực 5 phút.", function ($m) use ($toEmail) {
            $m->to($toEmail)->subject('Mã xác nhận đăng ký');
        });
    }

    protected function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard')->with('success', 'Xin chào Admin!'),
            default => redirect()->route('user.welcome')->with('success', 'Đăng nhập thành công!'),
        };
    }

    /** ================== Đăng ký ================== */
    public function showRegisterForm()
    {
        // Tính còn bao nhiêu giây cooldown để render xuống view
        [$ok, $remain] = $this->canSendRegisterCode();
        return view('auth.register', ['cooldownRemain' => $ok ? 0 : $remain]);
    }

    // Nút "Lấy mã" trên form đăng ký
    public function sendRegisterCode(Request $request)
    {
        $request->validate([
            'email' => ['required','email','max:255'],
        ]);

        // Không cho gửi nếu email đã tồn tại
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'ok' => false,
                'message' => 'Email đã tồn tại. Vui lòng dùng email khác.',
            ], 422);
        }

        // Cooldown 30s
        [$ok, $remain] = $this->canSendRegisterCode();
        if (!$ok) {
            return response()->json([
                'ok' => false,
                'message' => "Vui lòng đợi {$remain}s để gửi lại mã.",
                'remain' => $remain,
            ], 429);
        }

        $otp = $this->generateOtp();
        $this->putRegisterOtpToSession($request->email, $otp);
        $this->sendOtpMail($request->email, $otp);

        return response()->json([
            'ok' => true,
            'message' => 'Đã gửi mã xác nhận. Kiểm tra hộp thư của bạn.',
            'remain' => 30,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required','string','max:255'],
            'email'                 => ['required','email','max:255','unique:users,email'],
            'password'              => ['required','min:8','confirmed'],
            'code'                  => ['required','digits:6'],
        ]);

        // Xác thực mã trong session
        $ssEmail  = session('register.email');
        $ssCode   = session('register.code');
        $ssExpire = (int) session('register.expires_at');

        if (!$ssEmail || !$ssCode || !$ssExpire) {
            return back()->withErrors(['code' => 'Chưa nhận mã hoặc phiên đã hết. Vui lòng nhấn "Lấy mã".'])
                         ->withInput();
        }
        if (now()->timestamp > $ssExpire) {
            return back()->withErrors(['code' => 'Mã đã hết hạn. Vui lòng lấy mã mới.'])
                         ->withInput();
        }
        if ($data['email'] !== $ssEmail) {
            return back()->withErrors(['email' => 'Email này không khớp với email đã yêu cầu mã.'])
                         ->withInput();
        }
        if ($data['code'] !== $ssCode) {
            return back()->withErrors(['code' => 'Mã xác nhận không đúng.'])
                         ->withInput();
        }

        // Tạo user (đã xác minh email qua mã OTP)
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'customer',
            'email_verified_at' => now(), // coi như verified
        ]);

        // Xóa session liên quan tới đăng ký
        session()->forget(['register.email','register.code','register.expires_at','register.last_sent']);

        // Đăng nhập và điều hướng theo role
        Auth::login($user);
        return $this->redirectByRole($user)->with('success', 'Đăng ký thành công!');
    }

    /** ================== Đăng nhập (không cần mã) ================== */
    public function showLoginForm() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success','Bạn đã đăng xuất.');
    }
}
