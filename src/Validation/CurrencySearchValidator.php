<?php

namespace App\Validation;

class CurrencySearchValidator
{
    public function validate($isoCodes)
    {
        $errors = [];
        if (!empty($isoCodes) && is_array($isoCodes)) {
            $validIsoCodes = [];
            foreach ($isoCodes as $code) {
                // Check if each code contains only alpha characters using regex
                if (strlen($code) === 3 && preg_match('/^[a-z]+$/', $code)) {
                    $validIsoCodes[] = $code;
                }
            }
        }
        if (count($isoCodes) !== count($validIsoCodes)) {
           $errors = ['iso_codes' => ['custom' => 'Invalid data.']];
        }
        return $errors;
    }

}