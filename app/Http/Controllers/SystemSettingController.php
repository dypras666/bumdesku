<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Helpers\SystemSettingHelper;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        
        // Add protection status to each setting
        foreach ($settings as $group => $groupSettings) {
            foreach ($groupSettings as $setting) {
                $setting->is_protected = $this->isProtectedSetting($setting->key);
            }
        }
        
        return view('system-settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('system-settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:system_settings,key',
            'value' => 'nullable|string',
            'type' => 'required|in:text,file,number,boolean',
            'group' => 'required|string',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048'
        ]);

        $value = $request->value;

        // Handle file upload
        if ($request->hasFile('file') && $request->type === 'file') {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('system-settings', $filename, 'public');
            $value = $path;
        }

        SystemSetting::create([
            'key' => $request->key,
            'value' => $value,
            'type' => $request->type,
            'group' => $request->group,
            'description' => $request->description
        ]);

        // Auto clear cache after saving settings
        Artisan::call('optimize:clear');

        return redirect()->route('system-settings.index')
            ->with('success', 'Pengaturan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemSetting $systemSetting)
    {
        return view('system-settings.show', compact('systemSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemSetting $systemSetting)
    {
        return view('system-settings.edit', compact('systemSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemSetting $systemSetting)
    {
        $request->validate([
            'key' => 'required|string|unique:system_settings,key,' . $systemSetting->id,
            'value' => 'nullable|string',
            'type' => 'required|in:text,file,number,boolean',
            'group' => 'required|string',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048'
        ]);

        $value = $request->value;

        // Handle file upload
        if ($request->hasFile('file') && $request->type === 'file') {
            // Delete old file if exists
            if ($systemSetting->value && Storage::disk('public')->exists($systemSetting->value)) {
                Storage::disk('public')->delete($systemSetting->value);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('system-settings', $filename, 'public');
            $value = $path;
        } elseif ($request->type === 'file' && !$request->hasFile('file')) {
            // Keep existing file if no new file uploaded
            $value = $systemSetting->value;
        }

        $systemSetting->update([
            'key' => $request->key,
            'value' => $value,
            'type' => $request->type,
            'group' => $request->group,
            'description' => $request->description
        ]);

        // Auto clear cache after updating settings
        Artisan::call('optimize:clear');

        return redirect()->route('system-settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemSetting $systemSetting)
    {
        // Check if this is a protected/default setting
        if ($this->isProtectedSetting($systemSetting->key)) {
            return redirect()->route('system-settings.index')
                ->with('error', 'Pengaturan ini tidak dapat dihapus karena merupakan pengaturan sistem default.');
        }

        // Delete file if exists
        if ($systemSetting->type === 'file' && $systemSetting->value && Storage::disk('public')->exists($systemSetting->value)) {
            Storage::disk('public')->delete($systemSetting->value);
        }

        $systemSetting->delete();

        // Auto clear cache after deleting settings
        Artisan::call('optimize:clear');

        return redirect()->route('system-settings.index')
            ->with('success', 'Pengaturan berhasil dihapus.');
    }

    /**
     * Clear system cache manually
     */
    public function clearCache()
    {
        try {
            SystemSettingHelper::clearSystemCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Cache sistem berhasil dibersihkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a setting is protected from deletion
     */
    private function isProtectedSetting($key)
    {
        $protectedSettings = [
            // Company settings
            'company_name',
            'company_address',
            'company_phone',
            'company_email',
            'company_logo',
            
            // Financial settings
            'default_currency',
            'currency_symbol',
            'currency_position',
            'decimal_places',
            'tax_rate',
            
            // Journal settings
            'journal_prefix',
            'journal_start_number',
            'auto_journal_numbering',
            
            // System settings
            'timezone',
            'date_format',
            'time_format',
            'records_per_page',
            'backup_frequency',
            
            // Report settings
            'report_logo',
            'report_header',
            'report_footer'
        ];

        return in_array($key, $protectedSettings);
    }

    /**
     * Update multiple settings at once
     */
    public function updateBatch(Request $request)
    {
        // Validate file uploads
        $request->validate([
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048'
        ]);

        $settings = $request->input('settings', []);
        $files = $request->file('files', []);

        // Process regular settings first
        foreach ($settings as $key => $value) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting && $setting->type !== 'file') {
                $setting->update(['value' => $value]);
            }
        }

        // Process file uploads separately
        foreach ($files as $key => $file) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting && $setting->type === 'file' && $file && $file->isValid()) {
                // Delete old file if exists
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }

                // Store new file
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('system-settings', $filename, 'public');
                $setting->update(['value' => $path]);
            }
        }

        // Auto clear cache after batch updating settings
        Artisan::call('optimize:clear');

        return redirect()->route('system-settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
