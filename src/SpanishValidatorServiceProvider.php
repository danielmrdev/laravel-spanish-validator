<?php

namespace Danielmrdev\SpanishValidator;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class SpanishValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Get the path for publish the lang files
        $publishPath = $this->getLangPath();

        // Publish the lang files
        $this->publishes([
            __DIR__.'/../resources/lang' => $publishPath,
        ], 'lang');

        // Load the lang files
        $this->loadTranslationsFrom($publishPath, 'spanishValidator');
        Lang::addNamespace('spanishValidator', __DIR__.'/../resources/lang');

        // Add validators and messages
        $this->addValidators();
    }

    private function getLangPath()
    {
        // Laravel >= 9
        if ($this->isLaravel9OrHigher()) {
            return lang_path('vendor/spanishvalidator');
        }

        // Laravel < 9
        return resource_path('lang/vendor/spanishvalidator');
    }

    private function isLaravel9OrHigher()
    {
        return version_compare(app()->version(), '9.0', '>=');
    }

    private function addValidators()
    {
        // Tax Number: NIF or NIE or CIF
        Validator::extend('spanish_tax_number', 'Danielmrdev\\SpanishValidator\\InternalValidator@validateTaxNumber');
        Validator::replacer('spanish_tax_number', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.spanish_tax_number');
        });

        // Personal ID: NIF or NIE
        Validator::extend('spanish_personal_id', 'Danielmrdev\\SpanishValidator\\InternalValidator@validatePersonalId');
        Validator::replacer('spanish_personal_id', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.spanish_personal_id');
        });

        // NIF
        Validator::extend('nif', 'Danielmrdev\\SpanishValidator\\InternalValidator@validateNif');
        Validator::replacer('nif', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.nif');
        });

        // NIE
        Validator::extend('nie', 'Danielmrdev\\SpanishValidator\\InternalValidator@validateNie');
        Validator::replacer('nie', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.nie');
        });

        // CIF
        Validator::extend('cif', 'Danielmrdev\\SpanishValidator\\InternalValidator@validateCif');
        Validator::replacer('cif', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.cif');
        });

        // NSS
        Validator::extend('nss', 'Danielmrdev\\SpanishValidator\\InternalValidator@validateNss');
        Validator::replacer('nss', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.nss');
        });

        // IBAN
        Validator::extend('iban', 'Danielmrdev\\SpanishValidator\\InternalValidator@validateIban');
        Validator::replacer('iban', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.iban');
        });

        // Postal Code
        Validator::extend('spanish_postal_code', 'Danielmrdev\\SpanishValidator\\InternalValidator@validatePostalCode');
        Validator::replacer('spanish_postal_code', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.spanish_postal_code');
        });

        // Phone
        Validator::extend('spanish_phone', 'Danielmrdev\\SpanishValidator\\InternalValidator@validatePhone');
        Validator::replacer('spanish_phone', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.spanish_phone');
        });

        // Special NIF (K, L, M)
        Validator::extend('spanish_special_nif', 'Danielmrdev\\SpanishValidator\\InternalValidator@validateSpecialNif');
        Validator::replacer('spanish_special_nif', function ($message, $attribute, $rule, $parameters) {
            return __('spanishValidator::spanishValidator.spanish_special_nif');
        });
    }
}
