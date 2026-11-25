<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use App\Rules\MaxWords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    /** Pemohon mengajukan klaim ke sebuah barang. */
    public function store(Request $request, Item $item)
    {
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengklaim barang milik sendiri.');
        }
        if ($item->status !== 'tersedia') {
            return back()->with('error', 'Barang ini sudah tidak tersedia.');
        }
        if (Claim::where('item_id', $item->id)->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Anda sudah mengajukan klaim untuk barang ini.');
        }

        $data = $request->validate([
            'reason' => ['required', 'string', new MaxWords(500)],
        ], ['reason.required' => 'Alasan membutuhkan barang wajib diisi.']);

        Claim::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'reason' => $data['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('claims.index')->with('success', 'Pengajuan klaim berhasil dikirim.');
    }

    /** Riwayat pengajuan klaim milik pemohon. */
    public function index(Request $request)
    {
        $base = Claim::where('user_id', Auth::id());

        $counts = [
            'pending' => (clone $base)->where('status', 'pending')->count(),
            'diterima' => (clone $base)->where('status', 'diterima')->count(),
            'ditolak' => (clone $base)->where('status', 'ditolak')->count(),
        ];

        $claims = (clone $base)
            ->with(['item.donor', 'item.images'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $kw = '%' . $request->q . '%';
                $q->where(fn ($w) => $w->where('reason', 'like', $kw)
                    ->orWhereHas('item', fn ($i) => $i->where('name', 'like', $kw)));
            })
            ->when(in_array($request->status, ['pending', 'diterima', 'ditolak']), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('claims.index', compact('claims', 'counts'));
    }

    /** Edit alasan: hanya selama status masih pending. */
    public function update(Request $request, Claim $claim)
    {
        abort_unless($claim->user_id === Auth::id(), 403);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Alasan hanya bisa diubah selama status masih Pending.');
        }

        $data = $request->validate(['reason' => ['required', 'string', new MaxWords(500)]]);
        $claim->update(['reason' => $data['reason']]);

        return back()->with('success', 'Alasan pengajuan diperbarui.');
    }

    /** Batalkan pengajuan: hanya selama status masih pending. */
    public function destroy(Claim $claim)
    {
        abort_unless($claim->user_id === Auth::id(), 403);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Pengajuan hanya bisa dibatalkan selama status masih Pending.');
        }

        $claim->delete();

        return back()->with('success', 'Pengajuan dibatalkan.');
    }
}
