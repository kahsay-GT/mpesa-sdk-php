<?php

namespace Kahsaygt\Mpesa\Utilities;

use Kahsaygt\Mpesa\Exceptions\ValidationException;

class Validator
{
    public static function validate(array $data, array $rules): void
    {
        foreach ($rules as $field => $rule) {
            $ruleParts = explode('|', $rule);
            foreach ($ruleParts as $part) {
                if ($part === 'required' && !isset($data[$field])) {
                    throw new ValidationException("Field {$field} is required.");
                }
                if ($part === 'string' && isset($data[$field]) && !is_string($data[$field])) {
                    throw new ValidationException("Field {$field} must be a string.");
                }
                // Other rules...
            }
        }
    }
}