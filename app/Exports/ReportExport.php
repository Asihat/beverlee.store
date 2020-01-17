<?php

namespace App\Exports;

use App\Payments;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings {
    /**
     * @return \Illuminate\Support\Collection
     */
    private $_status;
    protected $_start = null;
    protected $_end = null;
    public function __construct($status, $start, $end ) {
        $this->_status = $status;
        $this->_start = $start;
        $this->_end = $end;
    }

    public function collection() {

        return Payments::select('data','status','description','updated_at')
            ->where('status', '=', $this->_status)
            ->where('updated_at', '>=', $this->_start)
            ->where('updated_at','<=', $this->_end)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function headings(): array {
        // TODO: Implement headings() method.
        return [
            'Название товара',
            'Статус',
            'Описание',
            'Дата'
        ];
    }
}
