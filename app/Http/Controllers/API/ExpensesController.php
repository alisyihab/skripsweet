<?php

namespace App\Http\Controllers\API;

use App\Expense;
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

        if (in_array($user->role, ['1, 3'])) {
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

        $users = User::whereIn('role', [0, 2])->get();
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
        $this->validate($request, [
           'id' => 'required|exists|expenses,id'
        ]);

        $expenses = Expense::with(['user' => 1]);
        Notification::send($expenses->user, new ExpensesNotification($expenses, $expenses->user));

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
}
