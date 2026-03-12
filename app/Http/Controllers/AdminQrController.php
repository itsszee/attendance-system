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
    public function index(Request $request)
    {
        $activeQr = OfficeQrCode::where('auto_generate', true)
            ->where('is_active', true)
            ->first();

        $query = OfficeQrCode::query();

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->where('valid_until', '>=', now());
            } elseif ($request->status === 'expired') {
                $query->where(function($q) {
                    $q->where('is_active', false)->orWhere('valid_until', '<', now());
                });
            }
        }

        if ($request->filled('auto')) {
            if ($request->auto === 'yes') {
                $query->where('auto_generate', true);
            } elseif ($request->auto === 'no') {
                $query->where('auto_generate', false);
            }
        }

        $codes = $query->orderBy('created_at', 'desc')->limit(50)->get();

        return view('admin.qr', compact('codes', 'activeQr'));
    }

    public function startAutoGenerate(Request $request)
    {
        OfficeQrCode::where('auto_generate', true)->update([
            'auto_generate' => false,
            'is_active' => false,
        ]);

        $qr = $this->generateQrCode(5); 
        $qr->update(['auto_generate' => true]);

        return redirect()->route('admin.qr.index')->with('success', 'Auto-generate QR dimulai!');
    }

    public function stopAutoGenerate()
    {
        OfficeQrCode::where('auto_generate', true)->update([
            'auto_generate' => false,
            'is_active' => false,
        ]);

        return redirect()->route('admin.qr.index')->with('success', 'Auto-generate QR dihentikan!');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'minutes' => 'nullable|integer|min:1|max:60'
        ]);

        $minutes = (int) $request->input('minutes', 10);
        $qr = $this->generateQrCode($minutes);

        return redirect()->route('admin.qr.index')->with('success', "Token created: {$qr->code} (valid {$minutes} minutes)");
    }

    public function getActiveQr()
    {
        $activeQr = OfficeQrCode::where('auto_generate', true)
            ->where('is_active', true)
            ->where('valid_until', '>=', now())
            ->first();

        if (!$activeQr) {
            $hasAutoGenerate = OfficeQrCode::where('auto_generate', true)->exists();
            
            if ($hasAutoGenerate) {
                OfficeQrCode::where('auto_generate', true)->update(['is_active' => false]);
                
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