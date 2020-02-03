<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Exports;

use Illuminate\Support\Arr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class Export
{
    /**
     * excel cells
     * @var array
     */
    public $cells = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

    /**
     * excel file name
     * @var
     */
    public $filename;

    public $data;

    /**
     * 定义数据库中字段名与excel列名的对应关系
     * @var array
     */
    public $columnMap = [];

    public function __construct($filename, $data)
    {
        $this->filename = $filename;

        $this->data = $data;

        $this->handle();
    }

    /**
     * handle function
     */
    public function handle()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $datas = $this->data;

        $keys = [];
        foreach ($datas as $row => $data) {
            $keys = array_keys($data);
            $values = array_values($data);

            foreach ($values as $index => $item) {
                $sheet->setCellValue($this->cells[$index].($row+2), $item);
            }
        }

        if ($keys) {
            foreach ($keys as $index => $key) {
                $sheet->setCellValue($this->cells[$index].'1', Arr::get($this->columnMap, $key, $key));
            }
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->filename);
    }
}
