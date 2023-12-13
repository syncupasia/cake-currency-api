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
            // Create a new Spreadsheet object
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
            // Optionally, you can throw a more specific exception or render an error page
            throw new InternalErrorException('Failed to export Excel. Please try again later.');
        }
    }
}


