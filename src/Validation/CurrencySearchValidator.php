<?php

namespace App\Validation;

class CurrencySearchValidator
{
    public function validate($isoCodes)
    {
        if (!empty($isoCodes) && is_array($isoCodes)) {
            $validIsoCodes = [];
            foreach ($isoCodes as $code) {
                // Check if each code contains only alpha characters using regex
                if (strlen($code) === 3 && preg_match('/^[a-z]+$/', $code)) {
                    $validIsoCodes[] = $code;
                }
            }
        }
        return (count($isoCodes) === count($validIsoCodes));
    }

}