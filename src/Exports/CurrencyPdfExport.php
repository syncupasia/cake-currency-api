<?php

namespace App\Exports;

use Mpdf\Mpdf;
use Cake\View\View;
use Cake\Utility\Text;
use Cake\Http\Exception\InternalErrorException;

class CurrencyPdfExport
{
    protected $currencies;

    public function __construct($currencies)
    {
        $this->currencies = $currencies;
    }

    public function export()
    {
        try {
            $view = new View();
            $view->disableAutoLayout();
            $view->set('currencies', $this->currencies);
            $html = $view->render('Exports/currencies_pdf');
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            $randomSuffix = substr(Text::uuid(),0,8);
            $filename = 'currencies'. date('Ymd') . '-' . $randomSuffix. '.pdf';
            return $mpdf->Output($filename, 'D');
            
        } catch (\Exception $e) {
            // Optionally, you can throw a more specific exception or render an error page
            throw new InternalErrorException('Failed to export Excel. Please try again later.');
        }
    }
}
