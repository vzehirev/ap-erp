<?php

/**
 * The original interface, lifted out of the Blade files unchanged - with two
 * exceptions, both of them typos rather than translations:
 *
 *   "Дании за периода" -> "Данни за периода"
 *   "Дa" in the sold-materials table used a Latin 'a'
 */

return [

    'nav' => [
        'bought' => 'Закупен материал',
        'sorted' => 'Сортиран материал',
        'ground' => 'Смлян материал',
        'washed' => 'Изпран материал',
        'granular' => 'Гранулиран материал',
        'sold' => 'Продаден материал',
        'expenses' => 'Разходи',
        'salaries' => 'Заплати',
        'prepaid' => 'Предплатени',
        'stock' => 'Налични материали',
        'reports' => 'Отчети',
        'reference' => 'Други',
        'logout' => 'ИЗХОД',
    ],

    'add' => [
        'bought' => 'Добави закупен материал',
        'sorted' => 'Добави сортиран материал',
        'ground' => 'Добави смлян материал',
        'washed' => 'Добави изпран материал',
        'granular' => 'Добави гранулиран материал',
        'sold' => 'Добави продаден материал',
        'expense' => 'Добави разход',
        'salary' => 'Добави заплата',
        'prepaid' => 'Добави предплата',
        'partner' => 'Добави партньор',
        'material' => 'Добави материал',
        'worker' => 'Добави служител',
    ],

    'actions' => [
        'add' => 'Добави',
        'close' => 'Затвори',
        'delete' => 'Изтрий',
        'another_material' => 'Още материал',
    ],

    'choose' => [
        'partner' => 'Избери партньор',
        'material' => 'Избери материал',
        'to_material' => 'Избери получен материал',
        'worker' => 'Избери служител',
        'workers' => 'Избери служител/и',
    ],

    'fields' => [
        'date' => 'Дата',
        'price' => 'Цена',
        'code' => 'Код',
        'material' => 'Материал',
        'invoice' => 'Фактура №',
        'invoice_number' => 'Номер на фактура',
        'from_material' => 'От материал',
        'to_material' => 'Получен материал',
        'quantity_received' => 'Получено количество',
        'bought_from' => 'Закупен от',
        'bought_material' => 'Закупен материал',
        'bought_quantity' => 'Закупено количество',
        'sold_to' => 'Продаден на',
        'sold_material' => 'Продаден материал',
        'sold_quantity' => 'Продадено количество',
        'paid' => 'Платен',
        'paid_salary' => 'Платена',
        'sorted_by' => 'Сортиран от',
        'ground_by' => 'Смлян от',
        'ground_quantity' => 'Смляно количество',
        'washed_by' => 'Изпран от',
        'granulated_by' => 'Гранулиран от',
        'discarded_quantity' => 'Изхвърлено количество (боклук)',
        'washed_quantity_before' => 'Изпрано количество (мръсно)',
        'washed_quantity_after' => 'Получено количество (чисто)',
        'washed_material_quantity' => 'Количество изпран материал',
        'granulate_quantity' => 'Количество получена гранула',
        'expense_type' => 'Вид/Тип разход',
        'worker' => 'Служител',
        'worker_name' => 'Име на служител',
        'amount' => 'Сума',
        'available_quantity' => 'Налично количество',
        'partner_name' => 'Име на партньор',
        'material_name' => 'Име на материал',
        'material_code' => 'Код на материал',
    ],

    'yes' => 'Да',
    'no' => 'Не',

    'pagination' => [
        'first' => 'Първа',
        'last' => 'Последна',
    ],

    'reports' => [
        'from_date' => 'От дата',
        'to_date' => 'До дата',
        'apply' => 'ОК',
        'clear' => 'Изчисти периода',
        'period' => 'Данни за периода:',
        'from_start' => 'От началото',
        'until_today' => 'До днес',
        'bought' => 'Закупени материали',
        'wasted' => 'Бракувани материали',
        'sorted' => 'Сортирани материали',
        'ground' => 'Смляни материали',
        'washed' => 'Изпрани материали',
        'granular' => 'Гранулирани материали',
        'sold' => 'Продадени материали',
        'total_quantity' => 'Общо количество:',
        'total_price' => 'Обща цена:',
        'average_price' => 'Средна цена:',
        'avg_price_column' => 'Средна цена',
        'wasted_quantity' => 'Бракувано количество',
        'from_material_code' => 'Код (от)',
        'to_material_code' => 'Код (получен)',
        'from_materials' => 'От материал/и',
        'type' => 'Вид/Тип',
        'total_income' => 'Общо приходи за периода:',
        'total_expenses' => 'Общо разходи за периода:',
        'profit' => 'Печалба:',
    ],

    'reference' => [
        'partners' => 'Партньори',
        'materials' => 'Материали',
        'workers' => 'Служители',
        'no_rows' => 'Няма записи.',
    ],

];
