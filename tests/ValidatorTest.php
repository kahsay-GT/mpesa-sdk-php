<?php

namespace Mpesa\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use Mpesa\Sdk\Utilities\Validator;
use Mpesa\Sdk\Exceptions\ValidationException;

class ValidatorTest extends TestCase
{
    public function testValidationFailsOnMissingRequiredField()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Field name is required.");

        Validator::validate([], ['name' => 'required|string']);
    }

    public function testValidationFailsOnTypeMismatch()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Field age must be a string.");

        Validator::validate(['age' => 123], ['age' => 'string']);
    }
}