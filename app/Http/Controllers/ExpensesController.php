<?php

namespace App\Http\Controllers;

use App\Models\Expense;

class ExpensesController extends Controller
{
    public function index()
    {
        return view('expenses.index', [
            'madeExpenses' => Expense::orderBy('made_on', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(100),
        ]);
    }
}
