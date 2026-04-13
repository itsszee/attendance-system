<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointRule;
use App\Models\FlexibilityItem;
use App\Models\PointLedger;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk mengelola pengaturan Integrity Wallet dari sisi Admin.
 * Ini mencakup manajemen aturan poin dan item di marketplace fleksibilitas.
 */
class IntegrityController extends Controller
{
    /**
     * Menampilkan dashboard Integrity Admin,
     * berisi daftar rules, items fleksibilitas, dan leaderboard poin.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $rules = PointRule::all();
        $items = FlexibilityItem::all();
        
        // Query untuk mencari id transaksi (ledger) terakhir untuk setiap user
        $latestLedgers = PointLedger::select('user_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('user_id');
            
        // Sub-query join untuk menyusun leaderboard berdasarkan current_balance tertinggi
        $leaderboard = PointLedger::joinSub($latestLedgers, 'latest', function ($join) {
                $join->on('point_ledgers.id', '=', 'latest.max_id');
            })
            ->with('user')
            ->orderBy('current_balance', 'desc')
            ->limit(10)
            ->get();
            
        return view('admin.integrity.index', compact('rules', 'items', 'leaderboard'));
    }

    /**
     * Menambahkan aturan poin (Point Rule) baru.
     * Rule ini akan menentukan bagaimana sistem otomatis memberi/mengurangi poin.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRule(Request $request)
    {
        // Validasi input parameter aturan baru
        $request->validate([
            'rule_name' => 'required|string',
            'target_role' => 'nullable|string',
            'condition_operator' => 'required|in:<,>,<=,>=,=,BETWEEN',
            'condition_value' => 'required',
            'point_modifier' => 'required|integer' // Nilai poin yang didapatkan (+ atau -)
        ]);
        
        PointRule::create($request->all());
        
        return back()->with('success', 'Rule berhasil ditambahkan');
    }
    
    /**
     * Menambahkan item kelonggaran (Flexibility Item) ke dalam marketplace.
     * Item ini nantinya dapat ditukar dengan poin oleh karyawan.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeItem(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string',
            'point_cost' => 'required|integer',
            'stock_limit' => 'nullable|integer'
        ]);
        
        FlexibilityItem::create($request->all());
        
        return back()->with('success', 'Item berhasil ditambahkan ke marketplace');
    }
    /**
     * Mengupdate aturan poin (Point Rule) yang ada.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRule(Request $request, $id)
    {
        $request->validate([
            'rule_name' => 'required|string',
            'target_role' => 'nullable|string',
            'condition_operator' => 'required|in:<,>,<=,>=,=,BETWEEN',
            'condition_value' => 'required',
            'point_modifier' => 'required|integer'
        ]);

        $rule = PointRule::findOrFail($id);
        $rule->update($request->all());

        return back()->with('success', 'Rule berhasil diupdate');
    }

    /**
     * Menghapus aturan poin (Point Rule).
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyRule($id)
    {
        $rule = PointRule::findOrFail($id);
        $rule->delete();

        return back()->with('success', 'Rule berhasil dihapus');
    }

    /**
     * Mengupdate item kelonggaran (Flexibility Item) di marketplace.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string',
            'point_cost' => 'required|integer',
            'stock_limit' => 'nullable|integer'
        ]);

        $item = FlexibilityItem::findOrFail($id);
        $item->update($request->all());

        return back()->with('success', 'Item berhasil diupdate');
    }

    /**
     * Menghapus item kelonggaran (Flexibility Item) dari marketplace.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyItem($id)
    {
        $item = FlexibilityItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Item berhasil dihapus');
    }
}
