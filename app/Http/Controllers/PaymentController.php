<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();
        
        if (!$applicant) {
            return redirect()->route('pendaftaran.create')->with('error', 'Silakan lengkapi pendaftaran terlebih dahulu.');
        }
        
        // Cek apakah sudah diverifikasi
        if (!$applicant->canMakePayment()) {
            $message = $applicant->status === 'REJECTED' 
                ? 'Berkas Anda ditolak. Silakan perbaiki berkas terlebih dahulu.'
                : 'Anda belum dapat melakukan pembayaran. Tunggu verifikasi berkas terlebih dahulu.';
            return redirect()->route('dashboard')->with('error', $message);
        }
        
        $payments = $applicant->payments()->orderBy('created_at', 'desc')->get();
        
        return view('payment.index', compact('applicant', 'payments'));
    }
    
    public function create()
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();
        
        if (!$applicant) {
            return redirect()->route('pendaftaran.create')->with('error', 'Silakan lengkapi pendaftaran terlebih dahulu.');
        }
        
        // Cek apakah sudah diverifikasi
        if (!$applicant->canMakePayment()) {
            $message = $applicant->status === 'REJECTED' 
                ? 'Berkas Anda ditolak. Silakan perbaiki berkas terlebih dahulu.'
                : 'Anda belum dapat melakukan pembayaran. Tunggu verifikasi berkas terlebih dahulu.';
            return redirect()->route('dashboard')->with('error', $message);
        }
        
        // Ambil semua payment items yang aktif
        $paymentItems = \App\Models\PaymentItem::where('is_active', true)->get();
        
        return view('payment.create', compact('applicant', 'paymentItems'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'exists:payment_items,id'
        ]);
        
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        
        // Hitung total amount
        $paymentItems = \App\Models\PaymentItem::whereIn('id', $request->items)->get();
        $totalAmount = $paymentItems->sum('price');
        
        $payment = Payment::create([
            'applicant_id' => $applicant->id,
            'invoice_number' => Payment::generateInvoiceNumber(),
            'amount' => $totalAmount,
            'payment_type' => 'multiple',
            'payment_method' => 'qris',
            'status' => 'pending',
            'expired_at' => Carbon::now()->addDays(3),
            'notes' => 'Pembayaran multiple items: ' . $paymentItems->pluck('name')->join(', ')
        ]);
        
        // Simpan detail items
        foreach ($paymentItems as $item) {
            \App\Models\PaymentDetail::create([
                'payment_id' => $payment->id,
                'payment_item_id' => $item->id,
                'quantity' => 1,
                'price' => $item->price,
                'subtotal' => $item->price
            ]);
        }
        
        return redirect()->route('payment.show', $payment->id)->with('success', 'Invoice pembayaran berhasil dibuat!');
    }
    
    public function show($id)
    {
        $payment = Payment::whereHas('applicant', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        
        return view('payment.show', compact('payment'));
    }
    
    public function pay(Request $request, $id)
    {
        $payment = Payment::whereHas('applicant', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        
        if (!$payment->canBePaid()) {
            return redirect()->back()->with('error', 'Pembayaran tidak dapat diproses.');
        }
        
        try {
            // Simulasi pembayaran berhasil
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'transaction_id' => 'TXN-' . time(),
                'reference_number' => 'REF-' . strtoupper(uniqid()),
                'verified_by' => auth()->id(),
                'verified_at' => now()
            ]);
            
            // Update status applicant langsung ke PAYMENT_VERIFIED untuk pengumuman
            $payment->applicant->update(['status' => 'PAYMENT_VERIFIED']);
        
        // Kirim notifikasi pembayaran berhasil
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'title' => '✅ Pembayaran Berhasil!',
            'message' => 'Pembayaran Anda berhasil diproses. Menunggu pengumuman hasil seleksi dari sekolah.',
            'type' => 'success',
            'is_read' => false
        ]);
        
            return redirect()->route('payment.show', $payment->id)->with('success', 'Pembayaran berhasil! Terima kasih.');
        } catch (\Exception $e) {
            \Log::error('Payment Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }
    
    public function receipt($id)
    {
        $payment = Payment::whereHas('applicant', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        
        if (!$payment->isPaid()) {
            return redirect()->route('payment.show', $id)->with('error', 'Pembayaran belum selesai.');
        }
        
        return view('payment.receipt', compact('payment'));
    }
    
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);
        
        $payment = Payment::whereHas('applicant', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($payment->receipt_path && Storage::disk('public')->exists($payment->receipt_path)) {
                Storage::disk('public')->delete($payment->receipt_path);
            }
            
            $file = $request->file('receipt');
            $filename = 'receipt_' . $payment->invoice_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_receipts', $filename, 'public');
            
            $payment->update([
                'receipt_path' => $path,
                'status' => 'paid', // Langsung paid setelah upload bukti
                'verified_by' => auth()->id(),
                'verified_at' => now()
            ]);
            
            // Update status applicant langsung ke PAYMENT_VERIFIED
            $payment->applicant->update(['status' => 'PAYMENT_VERIFIED']);
            
            // Kirim notifikasi ke murid
            \App\Models\Notification::create([
                'user_id' => auth()->id(),
                'title' => '✅ Pembayaran Terverifikasi!',
                'message' => 'Bukti pembayaran berhasil diverifikasi! Menunggu pengumuman hasil seleksi dari sekolah.',
                'type' => 'success',
                'is_read' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.'
            ]);
        }
        
        return response()->json(['error' => 'Gagal upload bukti pembayaran'], 400);
    }
    
    private function getPaymentAmount($paymentType, $applicant)
    {
        switch ($paymentType) {
            case 'registration':
                return $applicant->wave->biaya_daftar ?? 150000;
            case 'uniform':
                return 500000;
            case 'book':
                return 300000;
            case 'other':
                return 100000;
            default:
                return 150000;
        }
    }
}