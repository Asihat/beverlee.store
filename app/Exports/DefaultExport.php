<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Payments;
class DefaultExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Payments::all('description','status','updated_at');
    }
    public function headings(): array {
        // TODO: Implement headings() method.
        return [
            'Название товара',
            'Статус',
            'Дата'
        ];
    }
}
