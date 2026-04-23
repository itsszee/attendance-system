<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\SatisfactionRating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HelpdeskController extends Controller
{
    /**
     * Template saran auto-reply berdasarkan keyword.
     */
    private array $autoReplySuggestions = [
        'qr|scan|barcode' => [
            'label' => 'Masalah QR / Scan',
            'text'  => "Halo, terima kasih telah menghubungi helpdesk.\n\nUntuk masalah QR Code, mohon pastikan:\n1. Kamera tidak terhalang dan bersih.\n2. QR Code di layar masih aktif (belum expired — QR diperbarui setiap beberapa menit).\n3. Coba refresh halaman dan scan ulang.\n4. Pastikan browser memiliki izin akses kamera.\n\nJika masalah berlanjut, silakan hubungi operator terdekat. Terima kasih.",
        ],
        'lokasi|gps|location|koordinat' => [
            'label' => 'Masalah Lokasi / GPS',
            'text'  => "Halo, terima kasih telah menghubungi helpdesk.\n\nUntuk masalah deteksi lokasi, mohon pastikan:\n1. GPS pada perangkat Anda sudah diaktifkan.\n2. Browser diberi izin akses lokasi (Allow Location).\n3. Coba buka halaman absensi di luar ruangan untuk sinyal GPS lebih baik.\n4. Jika menggunakan WiFi, pastikan tidak ada VPN aktif.\n\nTerima kasih atas kesabaran Anda.",
        ],
        'lupa|absen|terlambat|koreksi' => [
            'label' => 'Lupa / Koreksi Absen',
            'text'  => "Halo, terima kasih telah menghubungi helpdesk.\n\nUntuk pengajuan koreksi absensi, silakan:\n1. Buka menu Pengajuan Cuti/Izin di sidebar.\n2. Pilih tipe pengajuan yang sesuai.\n3. Sertakan keterangan dan bukti jika ada.\n\nAdmin akan memproses pengajuan Anda dalam 1×24 jam kerja. Terima kasih.",
        ],
        'password|login|akun|masuk' => [
            'label' => 'Masalah Login / Akun',
            'text'  => "Halo, terima kasih telah menghubungi helpdesk.\n\nUntuk masalah login, coba langkah berikut:\n1. Pastikan email dan password sudah benar.\n2. Gunakan fitur \"Lupa Password\" di halaman login.\n3. Kosongkan cache browser dan coba lagi.\n\nJika akun terkunci atau tidak bisa diakses sama sekali, admin akan mereset akun Anda. Terima kasih.",
        ],
    ];

    /**
     * Dashboard antrian tiket masuk.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['reporter', 'operator'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->paginate(15)->withQueryString();

        $stats = [
            'total'       => Ticket::count(),
            'open'        => Ticket::open()->count(),
            'in_progress' => Ticket::inProgress()->count(),
            'closed'      => Ticket::closed()->count(),
        ];

        return view('admin.helpdesk.index', compact('tickets', 'stats'));
    }

    /**
     * Detail tiket untuk operator.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['reporter', 'operator', 'responses.responder', 'rating']);
        $suggestions = $this->getSuggestions($ticket->subject . ' ' . $ticket->description);

        return view('admin.helpdesk.show', compact('ticket', 'suggestions'));
    }

    /**
     * Operator membalas tiket.
     */
    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:2',
        ]);

        
        if (! $ticket->first_response_at) {
            $ticket->update([
                'first_response_at' => now(),
                'operator_id'       => Auth::id(),
                'status'            => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
            ]);
        }

        TicketResponse::create([
            'ticket_id'    => $ticket->id,
            'responder_id' => Auth::id(),
            'message'      => $request->message,
            'is_auto_reply'=> false,
        ]);

        return redirect()->route('admin.helpdesk.show', $ticket)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Update status tiket.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $update = ['status' => $request->status];

        if ($request->status === 'closed' && ! $ticket->resolved_at) {
            $update['resolved_at'] = now();
        }

        // Assign operator jika belum ada
        if (! $ticket->operator_id) {
            $update['operator_id'] = Auth::id();
        }

        $ticket->update($update);

        return redirect()->route('admin.helpdesk.show', $ticket)
            ->with('success', 'Status tiket berhasil diperbarui.');
    }

    /**
     * Dashboard analitik performa helpdesk.
     */
    public function dashboard()
    {
        // Avg response time 
        $ticketsWithResponse = Ticket::whereNotNull('first_response_at')->get(['created_at', 'first_response_at']);
        $avgResponseMinutes  = $ticketsWithResponse->count()
            ? $ticketsWithResponse->avg(fn($t) => $t->created_at->diffInMinutes($t->first_response_at))
            : null;

        $summary = [
            'total'        => Ticket::count(),
            'open'         => Ticket::open()->count(),
            'in_progress'  => Ticket::inProgress()->count(),
            'closed'       => Ticket::closed()->count(),
            'avg_response' => $avgResponseMinutes,
            'avg_rating'   => SatisfactionRating::avg('score'),
        ];

        // Rata-rata response time per operator
        $operatorStats = User::where('role', 'admin')
            ->withCount(['operatedTickets as total_handled' => fn($q) => $q->where('status', 'closed')])
            ->with(['operatedTickets' => fn($q) => $q->whereNotNull('first_response_at')])
            ->get()
            ->map(function ($user) {
                $tickets = $user->operatedTickets->filter(fn($t) => $t->first_response_at);
                $avgResponse = $tickets->count()
                    ? $tickets->avg(fn($t) => $t->created_at->diffInMinutes($t->first_response_at))
                    : null;

                $avgRating = SatisfactionRating::whereIn(
                    'ticket_id',
                    $user->operatedTickets->pluck('id')
                )->avg('score');

                return [
                    'name'         => $user->name,
                    'total_handled'=> $user->total_handled,
                    'avg_response' => $avgResponse ? round($avgResponse, 1) : '-',
                    'avg_rating'   => $avgRating   ? round($avgRating, 1)   : '-',
                ];
            })
            ->sortByDesc('total_handled')
            ->values();

        // Distribusi rating 
        $rawRating = SatisfactionRating::selectRaw('score, COUNT(*) as total')
            ->groupBy('score')
            ->orderBy('score')
            ->pluck('total', 'score');

        
        $ratingDistribution = collect(range(1, 5))->mapWithKeys(
            fn($s) => [$s => (int) ($rawRating[$s] ?? 0)]
        );

        // Tiket per bulan (6 bulan terakhir)
        $sixMonthsAgo = now()->startOfMonth()->subMonths(5);
        $rawTickets   = Ticket::where('created_at', '>=', $sixMonthsAgo)
            ->get(['created_at'])
            ->groupBy(fn($t) => $t->created_at->format('Y-m'))
            ->map->count();

        // Buat array 6 bulan berurutan dengan label bulan yang terbaca
        $bulanId = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $ticketsPerMonth = collect();
        for ($i = 5; $i >= 0; $i--) {
            $dt    = now()->subMonths($i);
            $month = $dt->format('Y-m');
            $label = $bulanId[(int)$dt->format('n') - 1] . ' ' . $dt->format('Y');
            $ticketsPerMonth[$label] = $rawTickets[$month] ?? 0;
        }

        return view('admin.helpdesk.dashboard', compact(
            'summary',
            'operatorStats',
            'ratingDistribution',
            'ticketsPerMonth'
        ));
    }

    /**
     * Cari saran auto-reply berdasarkan konten tiket.
     */
    private function getSuggestions(string $content): array
    {
        $content = strtolower($content);
        $found   = [];

        foreach ($this->autoReplySuggestions as $pattern => $suggestion) {
            if (preg_match('/(' . $pattern . ')/i', $content)) {
                $found[] = $suggestion;
            }
        }

        return $found;
    }
}
