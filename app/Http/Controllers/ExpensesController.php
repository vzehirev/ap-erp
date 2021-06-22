<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;

class ExpensesController extends Controller
{
    function index()
    {
        $madeExpenses = Expense::orderBy('made_on', 'desc')->paginate(100);

        return view('expenses.index', ['madeExpenses' => $madeExpenses]);
    }

    function store(StoreExpenseRequest $request)
    {
        Expense::create($request->validated());

        return back()->with('success', 'Успешно добавен разход.');
    }

    function delete(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Успешно изтрит разход.');
    }
}
