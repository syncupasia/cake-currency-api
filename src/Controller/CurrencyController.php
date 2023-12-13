<?php
declare(strict_types=1);

namespace App\Controller;

use App\Exports\CurrencyPdfExport;
use App\Exports\CurrencyExcelExport;
use App\View\CurrencySearchResultsView;
use App\Services\CurrencyConversionService;
use App\Validation\CurrencySearchValidator;
use App\Validation\CurrencyConvertValidator;

/**
 * Currency Controller
 *
 * @property \App\Model\Table\CurrencyTable $Currency
 * @method \App\Model\Entity\Currency[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CurrencyController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $isoCodes = $this->request->getQuery('iso_codes');
        if (!empty($isoCodes)) {
            $validator = new CurrencySearchValidator();
            if (!$validator->validate($isoCodes)) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['message' => 'Request failed', 'errors' => ['iso_codes' => ['custom' => 'Invalid data.']]]));
            }
            $currencies = $this->Currency->find()->whereInList('iso_code', $isoCodes);
        } else {
            $currencies = $this->Currency->find()->all();
        }

        $format = $this->request->getQuery('format', 'json');
        if ($format === 'excel') {
            $currencyExport = new CurrencyExcelExport($currencies);
            $export = $currencyExport->export();
            return $this->response->withType('xlsx')
                    ->withHeader('Content-Disposition', "attachment;filename=\"{$export['filename']}\"")
                    ->withBody($export['stream']);


        } elseif ($format === 'pdf') {
            $currencyExport = new CurrencyPdfExport($currencies);
            return $currencyExport->export();

        } else {
            $this->set('currencies', $currencies);
            $this->viewBuilder()->setClassName(CurrencySearchResultsView::class);
            $this->viewBuilder()->setOption('serialize', ['currencies']);
        }
    }

    public function convert($base_currency, $target_currency, CurrencyConversionService $conversionService)
    {
        $validator = new CurrencyConvertValidator();
        $data['base_currency'] = $base_currency;
        $data['target_currency'] = $target_currency;
        $data['amount'] = $this->request->getQuery('amount') ?? 1;
        $errors = $validator->validate($data);

        if (empty($errors)) 
        {
            // $conversionService - example: add service to container in Application.php
            $amount = $conversionService->calculate($data);
            return $this->response
                ->withStatus(200)
                ->withType('application/json')
                ->withStringBody(json_encode(['amount' => $amount]));
        } else {
            return $this->response
                ->withStatus(422)
                ->withType('application/json')
                ->withStringBody(json_encode(['message' => 'Validation failed', 'errors' => $errors]));
        }
    }

}
