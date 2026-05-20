<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    /**
     * Halaman daftar notifikasi user yang sedang login.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');

        $query = Notifikasi::where('user_id', auth()->id());

        match ($filter) {
            'hari_ini'  => $query->whereDate('created_at', Carbon::today()),
            'kemarin'   => $query->whereDate('created_at', Carbon::yesterday()),
            '7_hari'    => $query->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay()),
            '30_hari'   => $query->where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay()),
            default     => null,
        };

        $notifikasi = $query->latest()->paginate(20)->appends(['filter' => $filter]);

        return view('notifikasi.index', compact('notifikasi', 'filter'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca,
     * lalu redirect ke URL-nya (jika ada).
     */
    public function markRead($id)
    {
        $notif = Notifikasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->update(['dibaca' => true]);

        $redirectUrl = $notif->url ?? route('notifikasi.index');

        // Jika request via AJAX kembalikan JSON, otherwise redirect
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Tandai SEMUA notifikasi user sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        Notifikasi::where('user_id', auth()->id())
            ->update(['dibaca' => true]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Ambil 10 notifikasi belum dibaca (untuk dropdown topbar).
     */
    public function getUnread()
    {
        $notif = Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'count' => $notif->count(),
            'data'  => $notif,
        ]);
    }
}
