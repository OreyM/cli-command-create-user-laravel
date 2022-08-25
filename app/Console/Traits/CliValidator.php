<?php

namespace App\Console\Traits;

use App\Exceptions\ValidationCliException;
use Illuminate\Support\Facades\Validator;

trait CliValidator
{
    /**
     * @param callable $callback
     * @param array $rules
     * @return string
     * @throws ValidationCliException
     */
    protected function validate(callable $callback, array $rules): string
    {
        $validator = Validator::make([$rules[0] => $value = $callback()], [$rules[0] => $rules[1]]);

        if ($validator->fails()) {
            throw new ValidationCliException($validator->errors()->first($rules[0]));
        }

        return $value;
    }
}
