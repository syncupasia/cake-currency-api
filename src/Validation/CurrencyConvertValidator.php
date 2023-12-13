<?php

namespace App\Validation;

use Cake\Validation\Validator;

class CurrencyConvertValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('base_currency')
            ->notEmptyString('base_currency', 'Base currency is required')
            ->add('base_currency', 'custom', [
                'rule' => ['custom', '/^[a-z]+$/'],
                'message' => 'Base currency must contain only alphabetic and lowercase characters'
            ])
            ->add('base_currency', 'length', [
                'rule' => ['lengthBetween', 3, 3],
                'message' => 'Base currency must be 3 characters long'
            ]);

        $this->requirePresence('target_currency')
            ->notEmptyString('target_currency', 'Target currency is required')
            ->add('target_currency', 'custom', [
                'rule' => ['custom', '/^[a-z]+$/'],
                'message' => 'Target currency must contain only alphabetic and lowercase characters'
            ])
            ->add('target_currency', 'length', [
                'rule' => ['lengthBetween', 3, 3],
                'message' => 'Target currency must be 3 characters long'
            ]);

        $this->add('amount', 'numeric', [
            'rule' => 'numeric',
            'message' => 'Amount must be a numeric value',
        ])
        ->add('amount', 'gt', [
            'rule' => ['comparison', '>', 0],
            'message' => 'Amount must be greater than 0',
        ]);
        
    }

}