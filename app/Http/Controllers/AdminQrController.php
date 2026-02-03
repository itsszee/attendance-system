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
        $codes = OfficeQrCode::orderBy('created_at', 'desc')->limit(50)->get();

        return view('admin.qr', compact('codes'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'minutes' => 'nullable|integer|min:1|max:60'
        ]);

        $minutes = (int) $request->input('minutes', 10);

        $code = strtoupper(bin2hex(random_bytes(4)));

        $now = now();

        
        $qrCode = new QrCode($code);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        
        $filename = 'qr_' . $code . '.png';
        $path = 'qr-codes/' . $filename;
        Storage::disk('public')->put($path, $result->getString());

        $qr = OfficeQrCode::create([
            'code' => $code,
            'qr_image_path' => $path,
            'valid_from' => $now,
            'valid_until' => $now->copy()->addMinutes($minutes),
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.qr.index')->with('success', "Token created: {$qr->code} (valid {$minutes} minutes)");
    }
}
