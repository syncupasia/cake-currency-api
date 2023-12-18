<?php

namespace App\Exports;

use Cake\Utility\Text;
use Cake\Http\CallbackStream;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Http\Exception\InternalErrorException;

class CurrencyExcelExport
{
    protected $currencies;

    public function __construct($currencies)
    {
        $this->currencies = $currencies;
    }

    public function export()
    {
        try {
            $spreadsheet = new Spreadsheet();

            // Create a new worksheet
            $sheet = $spreadsheet->getActiveSheet();

            // Add headers
            $headers = ['ISO', 'Currency', 'Previous Rate', 'Current Rate'];
            $sheet->fromArray([$headers], null, 'A1');

            // Add data
            $data = $this->currencies->map(function ($currency) {
                        return [
                            $currency->iso_code,
                            $currency->name,
                            $currency->previous_rate,
                            $currency->current_rate,
                        ];
                    })->toArray();
            $count = count($this->currencies);
            $rowStart = 1;
            $rowEnd = 1 + $count;
            $columnArray = ['A','B','C','D'];
            foreach ($columnArray as $column) {
                $sheet->getStyle($column.'1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF66CDAA');
                $sheet->getStyle($column.'1')->getFont()->setBold( true );
            }
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF708090'],
                    ],                         
                ],
            ];
            foreach ($columnArray as $column) {
                $cellRange = $column.$rowStart.':'.$column.$rowEnd;
                $sheet->getStyle($cellRange)->applyFromArray($styleArray);
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            $sheet->fromArray($data, null, 'A2');

            // Create a new Xlsx writer
            $writer = new Xlsx($spreadsheet);
            $randomSuffix = substr(Text::uuid(),0,8);
            $filename = 'currencies' . date('Ymd') . '-' . $randomSuffix. '.xlsx';
            $stream = new CallbackStream(function () use ($writer) {
                $writer->save('php://output');
            });
            return ['stream' => $stream, 'filename' => $filename];
            
        } catch (\Exception $e) {
            // Can do special handling in controller with this exception
            throw new InternalErrorException('Failed to export Excel. Please try again later.');
        }
    }
}


