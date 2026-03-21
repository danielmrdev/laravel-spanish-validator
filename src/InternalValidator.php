<?php

namespace Danielmrdev\SpanishValidator;

class InternalValidator
{
    protected $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function validateTaxNumber($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidTaxNumber($value);
    }

    public function validatePersonalId($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidPersonalId($value);
    }

    public function validateNif($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidNif($value);
    }

    public function validateNie($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidNie($value);
    }

    public function validateCif($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidCif($value);
    }

    public function validateNss($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidNss($value);
    }

    public function validateIban($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidIban($value);
    }

    public function validatePostalCode($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidPostalCode($value);
    }

    public function validatePhone($attribute, $value, $parameters, $validator)
    {
        return $this->validator->isValidPhone($value);
    }
}
