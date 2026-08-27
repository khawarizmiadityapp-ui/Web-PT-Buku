<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CompanySetting;
use App\Models\User;
use App\Models\UserPreference;
use App\Services\AuditLogService;
use App\Services\TotpService;
use App\Mail\MfaOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        // Ensure user has a TOTP secret for Google Authenticator
        if (empty($user->two_factor_secret)) {
            $user->two_factor_secret = TotpService::generateSecret(16);
            $user->save();
        }

        $secret = $user->two_factor_secret;
        $company = 'PT Buku Nusantara';
        $qrUri = TotpService::getOtpAuthUri($company, $user->email, $secret);
        $qrImageUrl = TotpService::getQrCodeImageUrl($qrUri, 180);
        $targetEmail = env('MFA_OVERRIDE_EMAIL') ?: $user->email;
        $preferences = UserPreference::forUser($user);

        return view('settings.profile', compact('user', 'preferences', 'secret', 'qrUri', 'qrImageUrl', 'targetEmail'));
    }

    public function system()
    {
        $company = CompanySetting::current();

        return view('settings.system', compact('company'));
    }

    public function audit(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', strtoupper($request->action_type));
        }

        $stats = [
            'total_24h' => AuditLog::where('created_at', '>=', now()->subDay())->count(),
            'security_alerts' => AuditLog::where('created_at', '>=', now()->subDay())
                ->whereIn('action_type', ['DELETE', 'SYSTEM'])
                ->count(),
            'active_users' => User::where('updated_at', '>=', now()->subHours(24))->count(),
        ];

        $logs = $query->paginate(15)->withQueryString();

        return view('settings.audit', compact('logs', 'stats'));
    }

    public function exportAudit(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', strtoupper($request->action_type));
        }

        $logs = $query->limit(5000)->get();

        $filename = 'audit_log_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Timestamp', 'User', 'Action Type', 'Description', 'Table', 'IP Address']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->name ?? 'System',
                    $log->action_type,
                    $log->description,
                    $log->subject_table ?? '-',
                    $log->ip_address ?? '-',
                ]);
            }

            fclose($file);
        };

        AuditLogService::log('SYSTEM', 'Exported audit log to CSV');

        return response()->stream($callback, 200, $headers);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'avatar.image' => 'File avatar harus berupa gambar yang valid',
            'avatar.mimes' => 'Format avatar harus JPG, JPEG, PNG, atau WEBP',
            'avatar.max' => 'Ukuran avatar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->withFragment('personal');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position' => $request->position,
            'bio' => $request->bio,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        AuditLogService::log('UPDATE', 'Updated user profile', 'users', $user->id);

        return back()->with('success', 'Profil berhasil diperbarui!')->withFragment('personal');
    }

    /**
     * Send Email OTP for password change verification
     */
    public function sendPasswordOtp(Request $request)
    {
        $user = Auth::user();
        $targetEmail = env('MFA_OVERRIDE_EMAIL') ?: $user->email;

        // Generate 6 digit OTP for password change
        $otp = sprintf('%06d', random_int(100000, 999999));
        $request->session()->put('password_change_otp', $otp);
        $request->session()->put('password_change_otp_expires_at', now()->addMinutes(10)->timestamp);

        try {
            Mail::to($targetEmail)->send(new MfaOtpMail(
                $user,
                $otp,
                10,
                $request->ip(),
                $request->userAgent()
            ));
            return back()->with('success', "Kode OTP (6 digit) untuk verifikasi ganti password telah dikirim ke email {$targetEmail}.")->withFragment('security');
        } catch (\Throwable $e) {
            Log::error("Failed sending password change OTP to {$targetEmail}: " . $e->getMessage());
            return back()->with('warning', "Gagal mengirim email OTP: {$e->getMessage()}")->withFragment('security');
        }
    }

    /**
     * Update account password with mandatory 2FA/OTP verification
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'two_factor_code' => 'required|string|size:6',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'two_factor_code.required' => 'Kode verifikasi 2FA/OTP wajib diisi',
            'two_factor_code.size' => 'Kode verifikasi 2FA/OTP harus 6 digit angka',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->withFragment('security');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                ->withInput()
                ->withFragment('security');
        }

        // Verify 2FA Code (Supports both Google Authenticator and Email OTP)
        $submittedCode = trim((string) $request->input('two_factor_code'));
        
        // 1. Check Google Authenticator TOTP
        $isTotpValid = TotpService::verifyCode($user->two_factor_secret, $submittedCode);

        // 2. Check Email OTP for password change
        $sessionOtp = (string) $request->session()->get('password_change_otp');
        $otpExpiresAt = $request->session()->get('password_change_otp_expires_at', 0);
        $isEmailOtpValid = ($sessionOtp !== '' && hash_equals($sessionOtp, $submittedCode) && now()->timestamp <= $otpExpiresAt);

        if (!$isTotpValid && !$isEmailOtpValid) {
            return back()->withErrors(['two_factor_code' => 'Kode verifikasi (2FA / Email OTP) salah atau sudah kedaluwarsa. Silakan periksa aplikasi Google Authenticator atau minta kode baru via email.'])
                ->withInput()
                ->withFragment('security');
        }

        // Clean up OTP session
        $request->session()->forget(['password_change_otp', 'password_change_otp_expires_at']);

        // Update password in database
        $user->update(['password' => Hash::make($request->new_password)]);

        $methodUsed = $isTotpValid ? 'Google Authenticator' : 'Email OTP';
        AuditLogService::log('UPDATE', "Changed account password with {$methodUsed} verification", 'users', $user->id);

        return back()->with('success', 'Password berhasil diperbarui dengan verifikasi keamanan 2FA!')->withFragment('security');
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $preferences = UserPreference::forUser($user);

        $preferences->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'stock_alerts' => $request->boolean('stock_alerts'),
            'sales_reports' => $request->boolean('sales_reports'),
        ]);

        AuditLogService::log('UPDATE', 'Updated notification preferences', 'user_preferences', $preferences->id);

        return back()->with('success', 'Pengaturan notifikasi berhasil disimpan!')->withFragment('notifications');
    }

    public function updateSystem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'timezone' => 'required|string|max:50',
            'email_notifications' => 'nullable|boolean',
            'stock_alerts' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $company = CompanySetting::current();

        $company->update([
            'app_name' => $request->app_name,
            'timezone' => $request->timezone,
            'email_notifications' => $request->boolean('email_notifications'),
            'stock_alerts' => $request->boolean('stock_alerts'),
        ]);

        AuditLogService::log('UPDATE', 'Updated system settings', 'company_settings', $company->id);

        return back()->with('success', 'Pengaturan sistem berhasil disimpan!');
    }

    public function updateCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'company_name.required' => 'Nama perusahaan wajib diisi',
            'logo.image' => 'File logo harus berupa gambar yang valid',
            'logo.mimes' => 'Format logo harus JPG, JPEG, PNG, atau WEBP',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $company = CompanySetting::current();

        $data = [
            'company_name' => $request->company_name,
            'tax_id' => $request->tax_id,
            'address' => $request->address,
        ];

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        AuditLogService::log('UPDATE', 'Updated company profile', 'company_settings', $company->id);

        return back()->with('success', 'Profil perusahaan berhasil disimpan!');
    }
}
