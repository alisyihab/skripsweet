<?php

namespace App\Http\Controllers\API;

use App\DetailTransaction;
use App\Payment;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'detail' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();
            $transaction = Transaction::create([
                'customer_id' => $request->customer_id['id'],
                'user_id' => $user->id,
                'amount' => 0
            ]);

            $amount = 0;
            foreach ($request->detail as $row) {
                if (!is_null($row['laundry_price'])) {
                    $subtotal = $row['laundry_price']['price'] * $row['qty'];
                    if ($row['laundry_price']['unit_types'] == 'Kilogram') {
                        $subtotal = $row['laundry_price']['price'] * ($row['qty']);
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
            return response()->json(['status' => 'success', 'data' => $transaction]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $transaction = Transaction::with(['customer', 'payment', 'detail', 'detail.product'])->find($id);
        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function completeItem(Request $request)
    {
        $this->validate($request, [
           'id' => 'required|exists,detail_transaction,id'
        ]);

        $transaction = DetailTransaction::with(['transaction.customer'])->find($request->id);
        $transaction->update(['status' => 1 ]);
        $transaction->transaction->customer()->update(['point' => $transaction->transaction->customer->point + 1]);

        return response()->json(['status' => 'success']);
    }

    public function makePayment(Request $request)
    {
        $this->validate($request, [
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::find($request->transaction_id);
            $customer_change = 0;
            if ($request->customer_change) {
                $customer_change = $request->amount - $transaction->amount;
                $transaction->customer()->update(['deposit' => $transaction->customer->deposit + $customer_change]);
            }

            Payment::create([
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'customer_change' => $customer_change,
                'type' => false
            ]);

            $transaction->update(['status' => 1]);
            DB::commit();

            return response()->json(['status' => 'success']);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'data' => $e->getMessage()
            ]);
        }
    }
}
