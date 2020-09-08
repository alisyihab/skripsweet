<?php

namespace App\Http\Controllers\API;

use App\Expense;
use App\FinancialRecords;
use App\Notifications\ExpensesNotification;
use App\User;
use App\Http\Resources\ExpenseCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExpensesController extends Controller
{
    /**
     * @return ExpenseCollection
     */
    public function index()
    {
        $user = request()->user();
        $expenses = Expense::with(['user'])->orderBy('created_at', 'DESC');

        if (request()->q != '') {
            $expenses = $expenses->where('description', 'LIKE', '%' . request()->q . '%');
        }

        if ($user->role == 1) {
            $expenses = $expenses->where('user_id', $user->id);
        }

        return (new ExpenseCollection($expenses->paginate(10)));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|string|max:150',
            'price' => 'required|integer',
            'note' => 'nullable|string'
        ]);

        $user = $request->user();
        $status = $user->role == 0 || $user->role == 2 ? 1:0;

        $request->request->add([
            'user_id' => $user->id,
            'status' => $status
        ]);

        $expenses = Expense::create($request->all());

        if ($user->role == 0) {
            FinancialRecords::create([
                'amount' => $request->price,
                'type' => 1,
                'note' => $request->description
            ]);
        }

        $users = User::whereIn('role', [0])->get();
        Notification::send($users, new ExpensesNotification($expenses, $user));

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function accept(Request $request)
    {
        $this->validate($request, ['id' => 'required|exists:expenses,id']);

        $expenses = Expense::with(['user'])->find($request->id);
        $expenses->update(['status' => 1]);

        Notification::send($expenses->user, new ExpensesNotification($expenses, $expenses->user));

        FinancialRecords::create([
            'amount' => $expenses->price,
            'type' => 1,
            'note' => $expenses->note
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function cancleRequest(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:expenses,id',
            'reason' => 'required|string'
        ]);

        $expenses = Expense::with(['user'])->find($request->id);
        $expenses->update([
            'status' => 2,
            'reason' => $request->reason
        ]);
        Notification::send($expenses->user, new ExpensesNotification($expenses, $expenses->user));

        return response()->json(['status' => 'success']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $expenses = Expense::with(['user'])->find($id);
        return response()->json(['status' => 'success', 'data' => $expenses]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'description' => 'required|string|max:150',
            'price' => 'required|integer',
            'note' => 'nullable|string'
        ]);
        $expenses = Expense::find($id);
        $expenses->update($request->except('id'));
        return response()->json(['status' => 'success']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $expenses = Expense::find($id);
        $expenses->delete();

        return response()->json(['status' => 'success']);
    }
}
