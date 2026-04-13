<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointLedger;
use App\Models\FlexibilityItem;
use App\Models\UserToken;

/**
 * Controller untuk mengelola Integrity Wallet di sisi pengguna (karyawan).
 */
class WalletController extends Controller
{
    /**
     * Menampilkan halaman utama Integrity Wallet pengguna.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil saldo terakhir pengguna dari tabel PointLedger
        $latestLedger = PointLedger::where('user_id', $user->id)->latest('id')->first();
        $balance = $latestLedger ? $latestLedger->current_balance : 0;
        
        // Mengambil riwayat poin, daftar item fleksibilitas, dan token yang dimiliki
        $ledgers = PointLedger::where('user_id', $user->id)->latest('id')->limit(20)->get();
        $items = FlexibilityItem::all();
        $tokens = UserToken::with('item')->where('user_id', $user->id)->latest('id')->get();
        
        // Menentukan level kedisiplinan berdasarkan total saldo poin
        $level = 'Pemula';
        if ($balance > 100) $level = 'Disiplin Elite';
        elseif ($balance > 50) $level = 'Disiplin Senior';
        elseif ($balance > 10) $level = 'Disiplin Junior';
        
        return view('wallet.index', compact('balance', 'level', 'ledgers', 'items', 'tokens'));
    }

    /**
     * Memproses pembelian item kelonggaran (Flexibility Token) menggunakan poin.
     * 
     * @param Request 
     * @param FlexibilityItem 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buyToken(Request $request, FlexibilityItem $item)
    {
        $user = Auth::user();
        
        // Cek saldo poin terakhir pengguna
        $latestLedger = PointLedger::where('user_id', $user->id)->latest('id')->first();
        $balance = $latestLedger ? $latestLedger->current_balance : 0;
        
        // Validasi apakah poin cukup untuk membeli item
        if ($balance < $item->point_cost) {
            return back()->with('error', 'Poin tidak mencukupi untuk membeli item ini.');
        }
        
        // Validasi batas stok item jika dikonfigurasi
        if ($item->stock_limit !== null) {
            if ($item->stock_limit <= 0) {
                return back()->with('error', 'Item ini sudah habis (Out of stock).');
            }
            // Kurangi stok item karena ada yang menukarkan
            $item->decrement('stock_limit');
        }
        
        // Mencatat token baru ke inventaris akun pengguna
        UserToken::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'AVAILABLE'
        ]);
        
        // Mengurangi poin melalui IntegrityService dan mencatat log transaksi
        app(\App\Services\IntegrityService::class)->addLedgerEntry(
            $user->id, 
            'SPEND', 
            -$item->point_cost, 
            "Beli item: " . $item->item_name
        );
        
        return back()->with('success', 'Berhasil menukar poin dengan token kelonggaran!');
    }
}
