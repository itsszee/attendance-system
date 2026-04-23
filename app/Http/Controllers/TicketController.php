<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\SatisfactionRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Daftar tiket milik user yang sedang login.
     */
    public function index()
    {
        $user    = Auth::user();
        $tickets = Ticket::where('reporter_id', $user->id)
            ->latest()
            ->get();

        $stats = [
            'total'       => $tickets->count(),
            'open'        => $tickets->where('status', 'open')->count(),
            'in_progress' => $tickets->where('status', 'in_progress')->count(),
            'closed'      => $tickets->where('status', 'closed')->count(),
        ];

        return view('tickets.index', compact('tickets', 'stats'));
    }

    /**
     * Form buat tiket baru.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Simpan tiket baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject'     => 'required|string|max:255',
            'priority'    => 'required|in:low,mid,high',
            'description' => 'required|string|min:10',
        ]);

        $validated['reporter_id'] = Auth::id();

        Ticket::create($validated);

        return redirect()->route('user.tickets.index')
            ->with('success', 'Tiket berhasil dibuat! Tim helpdesk akan segera menghubungi Anda.');
    }

    /**
     * Detail tiket + thread percakapan.
     */
    public function show(Ticket $ticket)
    {
        // Pastikan user hanya bisa lihat tiketnya sendiri
        abort_unless($ticket->reporter_id === Auth::id(), 403);

        $ticket->load(['responses.responder', 'operator', 'rating']);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * User membalas tiket.
     */
    public function reply(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->reporter_id === Auth::id(), 403);
        abort_if($ticket->status === 'closed', 403, 'Tiket sudah ditutup.');

        $request->validate([
            'message' => 'required|string|min:2',
        ]);

        TicketResponse::create([
            'ticket_id'    => $ticket->id,
            'responder_id' => Auth::id(),
            'message'      => $request->message,
            'is_auto_reply'=> false,
        ]);

        return redirect()->route('user.tickets.show', $ticket)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Submit rating kepuasan (hanya saat tiket sudah closed).
     */
    public function rate(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->reporter_id === Auth::id(), 403);
        abort_unless($ticket->status === 'closed', 403);
        abort_if($ticket->rating()->exists(), 403, 'Anda sudah memberikan rating.');

        $request->validate([
            'score'    => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        SatisfactionRating::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'score'     => $request->score,
            'feedback'  => $request->feedback,
        ]);

        return redirect()->route('user.tickets.show', $ticket)
            ->with('success', 'Terima kasih atas penilaian Anda!');
    }

    /**
     * AJAX: Full-Text Search tiket serupa (anti-duplikasi).
     */
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 3) {
            return response()->json([]);
        }

        try {
            $results = Ticket::whereFullText(['subject', 'description'], $q)
                ->where('reporter_id', Auth::id())
                ->select('id', 'ticket_code', 'subject', 'status', 'priority', 'created_at')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Fallback LIKE search jika DB tidak support FULLTEXT (SQLite)
            $results = Ticket::where(function ($query) use ($q) {
                $query->where('subject', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
            })
                ->select('id', 'ticket_code', 'subject', 'status', 'priority', 'created_at')
                ->limit(5)
                ->get();
        }

        return response()->json($results);
    }
}
