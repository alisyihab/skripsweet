<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\TransactionCollection;
use App\Http\Controllers\Controller;
use App\DetailTransaction;
use App\Notifications\TransactionNotification;
use App\Notifications\PaymentNotification;
use App\Payment;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use File;

class TransactionController extends Controller
{
    public function index()
    {
        $search = request()->q;
        $user = request()->user();

        $transaction = Transaction::with(['user', 'detail', 'customer'])
            ->orderBy('updated_at', 'DESC')
            ->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });

        if (in_array(request()->status, [0, 1, 2])) {
            $transaction = $transaction->where('status', request()->status);
        }

        if ($user->role != 0 && $user->role != 1) {
            $transaction = $transaction->where('customer_id', $user->id);
        }

        $transaction = $transaction->paginate(10);

        return new TransactionCollection($transaction);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'detail' => 'required',
        ], [
            'customer_id.required' => 'Filed tidak boleh kosong'
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();
            $transaction = Transaction::create([
                'invoice' => $this->generateInvoice(),
                'customer_id' => $request->customer_id['id'],
                'user_id' => $user->id,
                'amount' => 0,
            ]);

            $amount = 0;
            foreach ($request->detail as $row) {
                if (!is_null($row['laundry_price'])) {
                    $subtotal = $row['laundry_price']['price'] * $row['qty'];
                    if ($row['laundry_price']['unit_type'] == 'Kilogram') {
                        $subtotal = ($row['laundry_price']['price'] * $row['qty']) / 1000;
                    }

                    $start_date = Carbon::now();
                    $end_date = Carbon::now()->addHours($row['laundry_price']['service']);
                    if ($row['laundry_price']['service_type'] == 'Hari') {
                        $end_date = Carbon::now()->addDays($row['laundry_price']['service']);
                    }

                    DetailTransaction::create([
                        'transaction_id' => $transaction->id,
                        'laundry_price_id' => $row['laundry_price']['id'],
                        'laundry_type_id' => $row['laundry_price']['laundry_type_id'],

                        'start_date' => $start_date->format('Y-m-d H:i:s'),
                        'end_date' => $end_date->format('Y-m-d H:i:s'),

                        'qty' => $row['qty'],
                        'price' => $row['laundry_price']['price'],
                        'subtotal' => $subtotal
                    ]);

                    $amount += $subtotal;
                }
            }
            $transaction->update(['amount' => $amount]);
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $transaction = Transaction::with(['user','customer', 'payment', 'detail', 'detail.product'])->find($id);

        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }

    public function completeItem(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:detail_transactions,id'
        ]);

        $transaction = DetailTransaction::with(['transaction.customer'])->find($request->id);

        $transaction->update(['status' => 1]);
        
        $transaction->transaction->customer()->update(['point' => $transaction->transaction->customer->point + 1]);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function makePayment(Request $request)
    {
        
        $this->validate($request, [
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|integer',
        ]);
        
        DB::beginTransaction();
        try {
            $user = request()->user();

            $transaction = Transaction::find($request->transaction_id);

            $customer_change = 0;

            if (filter_var($request->via_deposit, FILTER_VALIDATE_BOOLEAN)) {
                if ($transaction->customer->deposit < $request->amount) {
                    return response()->json([
                        'status' => 'error', 
                        'data' => 'Deposit tidak cukup'
                    ]);
                }

                $transaction->customer()->update(['deposit' => $transaction->customer->deposit - $request->amount]);

            } else {
                if (filter_var($request->customer_change, FILTER_VALIDATE_BOOLEAN)) {
                    $customer_change = $request->amount - $transaction->amount;
                    $transaction->customer()->update(['deposit' => $transaction->customer->deposit + $customer_change]);
                }
            }
            
            $customer_change = $request->amount - $transaction->amount;

            $name = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = $request->id . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/transaction', $name);
            }

            if ($user->role != 0 && $user->role != 1 && $request->hasFile('photo') == null) {
                return response()->json([
                    'status' => 'error',
                    'data' => 'Bukti Transaksi tidak boleh kosong'
                ]);
            }

            if ($user->role != 0 && $user->role != 1 && $request->amount > $transaction->amount) {
                return response()->json([
                    'status' => 'error',
                    'data' => 'Nominal harus sesuai dengan tagihan'
                ]);
            }

            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'customer_change' => $customer_change,
                'type' => $request->via_deposit,
                'photo' => $name
            ]);
            

            if ($user->role != 0 && $user->role != 1) {
                $transaction = $transaction->update(['status' => 1]);
                $users = User::whereIn('role', [0, 1])->get();

                Notification::send($users, new PaymentNotification($payment, $user));
            } else {
                $transaction->update(['status' => 2]);                
            }            
            
            DB::commit();

            if ($user->role != 0 && $user->role != 1) {
                return response()->json([
                    'status' => 'berhasil',
                    'pesan' => 'Pembayaran berhasil dilakukan, harap tunggu pengecekan dari admin.'
                ]);
            }

            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'data' => $e->getMessage()]);
        }
    }
    
    public function generateInvoice()
    {
        $order = Transaction::orderBy('created_at', 'DESC');
        if ($order->count() > 0) {
            $order = $order->first();
            $explode = explode('-', $order->invoice);
            $count = $explode[1] + 1;
            return 'INV-' . $count;
        }

        return 'INV-1';
    }

    public function accept(Request $request)
    {
        $this->validate($request, ['id' => 'required|exists:transactions,id']);

        $transaction = Transaction::with(['detail.product'])->find($request->id);
        $transaction->update(['status' => 2]);

        $user = request()->user();
        
        Notification::send($transaction->customer, new TransactionNotification($transaction, $user));

        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }
}
