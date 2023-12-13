<?php

namespace App\View;

use Cake\View\JsonView;

class CurrencySearchResultsView extends JsonView
{
    public function render($template = null, $layout = null): string
    {
        // Assuming the data is an array with the key 'currencies'
        if (isset($this->viewVars['currencies'])) {
            $formattedData['data'] = [];

            foreach ($this->viewVars['currencies'] as $currency) {
                $formattedCurrency = [
                    'iso_code' => $currency['iso_code'],
                    'name' => $currency['name'],
                    'current_rate' => $currency['current_rate'],
                    'previous_rate' => $currency['previous_rate'],
                    'base_currency' => $currency['base_currency'],
                    'last_modified' => $currency['updated_at'], // Assuming updated_at is the last modified timestamp
                ];

                $formattedData['data'][] = $formattedCurrency;
            }

            return json_encode($formattedData);
        }

        return json_encode([]); // return no data if not match
    }
}
