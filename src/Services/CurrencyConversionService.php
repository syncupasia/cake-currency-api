<?php

namespace App\Services;

use Cake\ORM\TableRegistry;

class CurrencyConversionService
{
    /**
     * Calculate currency conversion
     * 
     * @param array $params (base_currency, target_currency, amount)
     * @return float|bool $convertedAmount
     */
    public function calculate(array $params) {
        if (!isset($params['amount'])) {
            // shouldn't have this case if we validated the request
            return false;
        }
        $params['base_currency'] = strtoupper($params['base_currency']);
        $params['target_currency'] = strtoupper($params['target_currency']);

        if ($params['base_currency'] === $params['target_currency']) {
            // Conversion to the same currency, no change needed
            return floatval($params['amount']);
        }
        $currencyModel = TableRegistry::getTableLocator()->get('Currency');
        $currencies = $currencyModel->find('all')->combine('iso_code', function ($currency) {
            return $currency;
        })->toArray();

        if (!isset($currencies[$params['base_currency']]) || !isset($currencies[$params['target_currency']])) {
            // Handle invalid currency ISO codes - shouldn't have this case if we validated the request
            return false;
        }

        $convertedAmount = ($currencies[$params['target_currency']]->current_rate / 
                                $currencies[$params['base_currency']]->current_rate) * $params['amount'];

        return $convertedAmount;
    }
}