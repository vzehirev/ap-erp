<?php

/**
 * English is new on this branch - the original ships Bulgarian only.
 *
 * Where a literal translation would read oddly the trade term is used instead:
 * the navigation is named after the process ("Sorting") rather than after the
 * material in its state ("Sorted material"), which is how the screens are
 * actually used.
 */

return [

    'nav' => [
        'bought' => 'Purchases',
        'sorted' => 'Sorting',
        'ground' => 'Grinding',
        'washed' => 'Washing',
        'granular' => 'Granulating',
        'sold' => 'Sales',
        'expenses' => 'Expenses',
        'salaries' => 'Wages',
        'prepaid' => 'Advances',
        'stock' => 'Stock on hand',
        'reports' => 'Reports',
        'reference' => 'Reference data',
        'logout' => 'SIGN OUT',
    ],

    'add' => [
        'bought' => 'Record a purchase',
        'sorted' => 'Record a sorting run',
        'ground' => 'Record a grinding run',
        'washed' => 'Record a washing run',
        'granular' => 'Record a granulating run',
        'sold' => 'Record a sale',
        'expense' => 'Record an expense',
        'salary' => 'Record a wage payment',
        'prepaid' => 'Record an advance',
        'partner' => 'Add a partner',
        'material' => 'Add a material',
        'worker' => 'Add an employee',
    ],

    'actions' => [
        'add' => 'Save',
        'close' => 'Close',
        'delete' => 'Delete',
        'another_material' => 'Another material',
    ],

    'choose' => [
        'partner' => 'Choose a partner',
        'material' => 'Choose a material',
        'to_material' => 'Choose the material produced',
        'worker' => 'Choose an employee',
        'workers' => 'Choose the crew',
    ],

    'fields' => [
        'date' => 'Date',
        'price' => 'Price',
        'code' => 'Code',
        'material' => 'Material',
        'invoice' => 'Invoice no.',
        'invoice_number' => 'Invoice number',
        'from_material' => 'From material',
        'to_material' => 'Material produced',
        'quantity_received' => 'Quantity produced (kg)',
        'bought_from' => 'Bought from',
        'bought_material' => 'Material',
        'bought_quantity' => 'Quantity bought (kg)',
        'sold_to' => 'Sold to',
        'sold_material' => 'Material',
        'sold_quantity' => 'Quantity sold (kg)',
        'paid' => 'Paid',
        'paid_salary' => 'Paid',
        'sorted_by' => 'Sorted by',
        'ground_by' => 'Ground by',
        'ground_quantity' => 'Quantity ground (kg)',
        'washed_by' => 'Washed by',
        'granulated_by' => 'Granulated by',
        'discarded_quantity' => 'Rejects (kg)',
        'washed_quantity_before' => 'Quantity in, unwashed (kg)',
        'washed_quantity_after' => 'Quantity out, washed (kg)',
        'washed_material_quantity' => 'Washed material used (kg)',
        'granulate_quantity' => 'Granulate produced (kg)',
        'expense_type' => 'Type of expense',
        'worker' => 'Employee',
        'worker_name' => 'Employee',
        'amount' => 'Amount',
        'available_quantity' => 'On hand (kg)',
        'partner_name' => 'Partner name',
        'material_name' => 'Material name',
        'material_code' => 'Material code',
    ],

    'yes' => 'Yes',
    'no' => 'No',

    'pagination' => [
        'first' => 'First',
        'last' => 'Last',
    ],

    'reports' => [
        'from_date' => 'From',
        'to_date' => 'To',
        'apply' => 'OK',
        'clear' => 'Clear the period',
        'period' => 'Showing:',
        'from_start' => 'from the beginning',
        'until_today' => 'up to today',
        'bought' => 'Materials bought',
        'wasted' => 'Waste booked',
        'sorted' => 'Materials sorted',
        'ground' => 'Materials ground',
        'washed' => 'Materials washed',
        'granular' => 'Materials granulated',
        'sold' => 'Materials sold',
        'total_quantity' => 'Total quantity:',
        'total_price' => 'Total value:',
        'average_price' => 'Average price:',
        'avg_price_column' => 'Average price',
        'wasted_quantity' => 'Quantity wasted (kg)',
        'from_material_code' => 'Code (from)',
        'to_material_code' => 'Code (produced)',
        'from_materials' => 'From material(s)',
        'type' => 'Type',
        'total_income' => 'Income for the period:',
        'total_expenses' => 'Costs for the period:',
        'profit' => 'Profit:',
    ],

    'reference' => [
        'partners' => 'Partners',
        'materials' => 'Materials',
        'workers' => 'Employees',
        'no_rows' => 'Nothing recorded.',
    ],

];
