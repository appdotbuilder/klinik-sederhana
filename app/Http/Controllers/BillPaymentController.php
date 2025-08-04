<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillPaymentController extends Controller
{
    /**
     * Process payment for bill.
     */
    public function store(Request $request, Bill $bill)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:' . $bill->total,
        ], [
            'paid_amount.required' => 'Jumlah bayar wajib diisi.',
            'paid_amount.min' => 'Jumlah bayar tidak boleh kurang dari total tagihan.',
        ]);

        if ($bill->status === 'paid') {
            return back()->with('error', 'Tagihan sudah lunas.');
        }

        $bill->processPayment($request->paid_amount);

        return redirect()->route('bills.show', $bill)
            ->with('success', 'Pembayaran berhasil diproses. Kembalian: Rp ' . number_format($bill->change_amount, 0, ',', '.'));
    }
}