<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CompanySetting;
use App\Models\User;
use App\Models\UserPreference;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $preferences = UserPreference::forUser($user);

        return view('settings.profile', compact('user', 'preferences'));
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
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->withFragment('security');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                ->withInput()
                ->withFragment('security');
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        AuditLogService::log('UPDATE', 'Changed account password', 'users', $user->id);

        return back()->with('success', 'Password berhasil diperbarui!')->withFragment('security');
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
