<?php
namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class UsersExport extends Export {

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function handle(){
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $datas = User::leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')->get()->toArray();

        foreach ($datas as $row => $data){

            $keys = array_keys($data);
            $values = array_values($data);

            foreach ($values as $index => $item){

                $sheet->setCellValue($this->cells[$index].($row+2), $item);
            }
        }
        if ($keys){
            foreach ($keys as $index => $key){
                $sheet->setCellValue($this->cells[$index].'1', $key);
            }
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->filename);
    }
}