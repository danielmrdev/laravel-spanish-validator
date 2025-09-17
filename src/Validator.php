<?php

namespace Orumad\SpanishValidator;

use PHP_IBAN\IBAN;

class Validator
{
    public function isValidTaxNumber(?string $value): bool
    {
        return
            $this->isValidNif($value) or
            $this->isValidNie($value) or
            $this->isValidCif($value);
    }

    public function isValidPersonalId(?string $value): bool
    {
        return
            $this->isValidNif($value) or
            $this->isValidNie($value);
    }

    public function isValidNif(?string $value): bool
    {
        if ($value) {
            $regEx = '/^[0-9]{8}[A-Z]$/i';

            $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';

            $value = strtoupper($value);

            if (preg_match($regEx, $value)) {
                return $letters[substr($value, 0, 8) % 23] == $value[8];
            }
        }

        return false;
    }

    public function isValidNie(?string $value): bool
    {
        if ($value) {
            $regEx = '/^[XYZ][0-9]{7}[A-Z]$/i';
            $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';

            $value = strtoupper($value);

            if (preg_match($regEx, $value)) {
                $replaced = str_replace(['X', 'Y', 'Z'], [0, 1, 2], $value);

                return $letters[substr($replaced, 0, 8) % 23] == $value[8];
            }
        }

        return false;
    }

    public function isValidCif(?string $value): bool
    {
        if ($value) {
            $regEx1 = '/^[ABEH][0-9]{8}$/i';
            $regEx2 = '/^[KPQS][0-9]{7}[A-J]$/i';
            $regEx3 = '/^[CDFGJLMNRUVW][0-9]{7}[0-9A-J]$/i';

            $value = strtoupper($value);

            if (preg_match($regEx1, $value) ||
                preg_match($regEx2, $value) ||
                preg_match($regEx3, $value)) {
                $digit = $value[strlen($value) - 1];
                $sum1 = 0;
                $sum2 = 0;

                for ($i = 1; $i < 8; $i++) {
                    if ($i % 2 == 0) {
                        $sum1 += intval($value[$i]);
                    } else {
                        $tot = (intval($value[$i]) * 2);
                        $par = 0;

                        for ($j = 0; $j < strlen($tot); $j++) {
                            $par += substr($tot, $j, 1);
                        }
                        $sum2 += $par;
                    }
                }

                $sum3 = intval($sum1 + $sum2).'';
                $sum4 = (10 - intval($sum3[strlen($sum3) - 1])) % 10;

                $letters = 'JABCDEFGHI';

                if ($digit >= '0' && $digit <= '9') {
                    return $digit == $sum4;
                }

                return strtoupper($digit) == $letters[$sum4];
            }
        }

        return false;
    }

    public function isValidNSS(?string $value): bool
    {
        if ($value) {
            $regEx = '/^[0-9]{12}$/i';

            if (preg_match($regEx, $value)) {
                $num1 = substr($value, 0, 2);
                $num2 = substr($value, 2, 8);
                $num3 = substr($value, 10, 2);
                $num4 = null;

                if ($num1 && $num2 && $num3) {
                    if ($num2 < 10000000) {
                        $num4 = $num2 + $num1 * 10000000;
                    } else {
                        $num4 = $num1.$num2;
                    }
                    $validacion = $num4 % 97;

                    if ($validacion == $num3) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function isValidIban(?string $value): bool
    {
        if ($value) {
            $iban = new IBAN();

            return $iban->Verify($value);
        }

        return false;
    }

    public function isValidPostalCode(?string $value): bool
    {
        if ($value) {
            $regEx = '/^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$/i';

            return preg_match($regEx, $value) === 1;
        }

        return false;
    }

    public function isValidPhone(?string $value): bool
    {
        if ($value) {
            $regEx = '/^[9|8|6|7][0-9]{8}$/i';

            return preg_match($regEx, $value) === 1;
        }

        return false;
    }
}
