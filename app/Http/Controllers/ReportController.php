<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, Item $item)
    {
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat melaporkan barang milik sendiri.');
        }
        if (Report::where('item_id', $item->id)->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Anda sudah melaporkan barang ini.');
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'reason.required' => 'Alasan melapor wajib diisi.',
            'reason.min' => 'Jelaskan alasan melapor minimal 10 karakter.',
        ]);

        Report::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'reason' => $data['reason'],
            'status' => 'baru',
        ]);

        return back()->with('success', 'Laporan terkirim. Admin akan meninjau barang ini.');
    }
}

