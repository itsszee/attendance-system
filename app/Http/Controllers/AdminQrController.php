<?php

namespace App\Http\Controllers;

use App\Models\OfficeQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class AdminQrController extends Controller
{
    public function index()
    {
        // Ambil QR code yang auto-generate (active)
        $activeQr = OfficeQrCode::where('auto_generate', true)
            ->where('is_active', true)
            ->first();

        // Ambil history QR codes
        $codes = OfficeQrCode::orderBy('created_at', 'desc')->limit(50)->get();

        return view('admin.qr', compact('codes', 'activeQr'));
    }

    // Start auto-generate
    public function startAutoGenerate(Request $request)
    {
        // Stop semua auto-generate yang lama
        OfficeQrCode::where('auto_generate', true)->update([
            'auto_generate' => false,
            'is_active' => false,
        ]);

        // Generate QR pertama
        $qr = $this->generateQrCode(5); // 5 menit
        $qr->update(['auto_generate' => true]);

        return redirect()->route('admin.qr.index')->with('success', 'Auto-generate QR dimulai!');
    }

    // Stop auto-generate
    public function stopAutoGenerate()
    {
        OfficeQrCode::where('auto_generate', true)->update([
            'auto_generate' => false,
            'is_active' => false,
        ]);

        return redirect()->route('admin.qr.index')->with('success', 'Auto-generate QR dihentikan!');
    }

    // Generate QR manual (legacy)
    public function generate(Request $request)
    {
        $request->validate([
            'minutes' => 'nullable|integer|min:1|max:60'
        ]);

        $minutes = (int) $request->input('minutes', 10);
        $qr = $this->generateQrCode($minutes);

        return redirect()->route('admin.qr.index')->with('success', "Token created: {$qr->code} (valid {$minutes} minutes)");
    }

    // API untuk frontend polling (cek QR terbaru)
    public function getActiveQr()
    {
        $activeQr = OfficeQrCode::where('auto_generate', true)
            ->where('is_active', true)
            ->where('valid_until', '>=', now())
            ->first();

        // Kalau QR udah expired, generate baru otomatis
        if (!$activeQr) {
            $hasAutoGenerate = OfficeQrCode::where('auto_generate', true)->exists();
            
            if ($hasAutoGenerate) {
                // Nonaktifkan yang lama
                OfficeQrCode::where('auto_generate', true)->update(['is_active' => false]);
                
                // Bikin yang baru
                $activeQr = $this->generateQrCode(5);
                $activeQr->update(['auto_generate' => true]);
            }
        }

        if ($activeQr) {
            return response()->json([
                'code' => $activeQr->code,
                'image_url' => asset('storage/' . $activeQr->qr_image_path),
                'valid_until' => $activeQr->valid_until->toIso8601String(),
                'seconds_remaining' => now()->diffInSeconds($activeQr->valid_until, false),
            ]);
        }

        return response()->json(null);
    }

    // Helper function untuk generate QR
    private function generateQrCode($minutes)
    {
        $code = strtoupper(bin2hex(random_bytes(4)));
        $now = now();

        $qrCode = new QrCode($code);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $filename = 'qr_' . $code . '.png';
        $path = 'qr-codes/' . $filename;
        Storage::disk('public')->put($path, $result->getString());

        return OfficeQrCode::create([
            'code' => $code,
            'qr_image_path' => $path,
            'valid_from' => $now,
            'valid_until' => $now->copy()->addMinutes($minutes),
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);
    }
}