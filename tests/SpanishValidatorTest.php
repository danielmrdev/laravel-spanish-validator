<?php

namespace Danielmrdev\SpanishValidator\Tests;

use Danielmrdev\SpanishValidator\Validator;
use PHPUnit\Framework\Attributes\Test;

class SpanishValidatorTest extends \PHPUnit\Framework\TestCase
{
    private $validator;

    public function setUp(): void
    {
        $this->validator = new Validator();
    }

    #[Test]
    public function is_a_valid_nif()
    {
        $this->assertTrue($this->validator->isValidNif('35899029P'));
        $this->assertTrue($this->validator->isValidNif('95433526V'));
        $this->assertTrue($this->validator->isValidNif('67662178L'));
        $this->assertTrue($this->validator->isValidNif('26459827Y'));
        $this->assertTrue($this->validator->isValidNif('09376604X'));
    }

    #[Test]
    public function is_not_a_valid_nif()
    {
        $this->assertFalse($this->validator->isValidNif('X5487441D'));
        $this->assertFalse($this->validator->isValidNif('26459827A'));
        $this->assertFalse($this->validator->isValidNif('A8692083W'));
        $this->assertFalse($this->validator->isValidNif('A869208'));
        $this->assertFalse($this->validator->isValidNif(null));
    }

    #[Test]
    public function is_a_valid_nie()
    {
        $this->assertTrue($this->validator->isValidNie('X5487441D'));
        $this->assertTrue($this->validator->isValidNie('X2792997S'));
        $this->assertTrue($this->validator->isValidNie('Y0005555A'));
        $this->assertTrue($this->validator->isValidNie('Y4712444B'));
        $this->assertTrue($this->validator->isValidNie('Z8546192H'));
    }

    #[Test]
    public function is_not_a_valid_nie()
    {
        $this->assertFalse($this->validator->isValidNie('Q9516803E'));
        $this->assertFalse($this->validator->isValidNie('A8692083W'));
        $this->assertFalse($this->validator->isValidNie('Z1361012'));
        $this->assertFalse($this->validator->isValidNie('Z1361012-Z'));
        $this->assertFalse($this->validator->isValidNie(null));
        // K, L, M are special NIF prefixes (BOE-A-1990-6394), not valid NIE prefixes
        $this->assertFalse($this->validator->isValidNie('K1234567L'));
        $this->assertFalse($this->validator->isValidNie('L1234567X'));
        $this->assertFalse($this->validator->isValidNie('M1234567Z'));
    }

    #[Test]
    public function is_a_valid_cif()
    {
        $this->assertTrue($this->validator->isValidCif('Q9516803E'));
        $this->assertTrue($this->validator->isValidCif('A46391553'));
        $this->assertTrue($this->validator->isValidCif('G83975433'));
        $this->assertTrue($this->validator->isValidCif('W8558600F'));
        $this->assertTrue($this->validator->isValidCif('B48259139'));
    }

    #[Test]
    public function is_not_a_valid_cif()
    {
        $this->assertFalse($this->validator->isValidCif('X5487441D'));
        $this->assertFalse($this->validator->isValidCif('A8692083W'));
        $this->assertFalse($this->validator->isValidCif('A46390553'));
        $this->assertFalse($this->validator->isValidCif('21361012S'));
        $this->assertFalse($this->validator->isValidCif(null));
    }

    #[Test]
    public function is_a_valid_spanish_tax_number()
    {
        $this->assertTrue($this->validator->isValidTaxNumber('67662178L'));
        $this->assertTrue($this->validator->isValidTaxNumber('B48259139'));
        $this->assertTrue($this->validator->isValidTaxNumber('Y4712444B'));
        $this->assertTrue($this->validator->isValidTaxNumber('09376604X'));
        $this->assertTrue($this->validator->isValidTaxNumber('A46391553'));
    }

    #[Test]
    public function is_not_a_valid_spanish_tax_number()
    {
        $this->assertFalse($this->validator->isValidTaxNumber('X5487401D'));
        $this->assertFalse($this->validator->isValidTaxNumber('A86920830'));
        $this->assertFalse($this->validator->isValidTaxNumber('B46390553'));
        $this->assertFalse($this->validator->isValidTaxNumber('21321012S'));
        $this->assertFalse($this->validator->isValidTaxNumber(null));
    }

    #[Test]
    public function is_a_valid_spanish_personal_id_number()
    {
        $this->assertTrue($this->validator->isValidPersonalId('67662178L'));
        $this->assertTrue($this->validator->isValidPersonalId('Y4712444B'));
        $this->assertTrue($this->validator->isValidPersonalId('09376604X'));
        $this->assertTrue($this->validator->isValidPersonalId('Z8546192H'));
        $this->assertTrue($this->validator->isValidPersonalId('26459827Y'));
    }

    #[Test]
    public function is_not_a_valid_spanish_personal_id_number()
    {
        $this->assertFalse($this->validator->isValidPersonalId('A46391553'));
        $this->assertFalse($this->validator->isValidPersonalId('X5487401D'));
        $this->assertFalse($this->validator->isValidPersonalId('B46390553'));
        $this->assertFalse($this->validator->isValidPersonalId('21321012S'));
        $this->assertFalse($this->validator->isValidPersonalId(null));
    }

    #[Test]
    public function is_a_valid_nss()
    {
        $this->assertTrue($this->validator->isValidNss('087894806929'));
        $this->assertTrue($this->validator->isValidNss('438509069151'));
        $this->assertTrue($this->validator->isValidNss('190250601711'));
        $this->assertTrue($this->validator->isValidNss('291917876947'));
        $this->assertTrue($this->validator->isValidNss('371419656725'));
    }

    #[Test]
    public function is_not_a_valid_nss()
    {
        $this->assertFalse($this->validator->isValidNss('291917896947'));
        $this->assertFalse($this->validator->isValidNss('071419656725'));
        $this->assertFalse($this->validator->isValidNss('438590069151'));
        $this->assertFalse($this->validator->isValidNss('08789480929'));
        $this->assertFalse($this->validator->isValidNss(null));
    }

    #[Test]
    public function is_a_valid_iban()
    {
        $this->assertTrue($this->validator->isValidIban('ES88 0211 6656 9861 8444 7681'));
        $this->assertTrue($this->validator->isValidIban('ES6120466547007633602928'));
        $this->assertTrue($this->validator->isValidIban('ES06 3127 5991 1947 2388 4544'));
        $this->assertTrue($this->validator->isValidIban('ES5915221496277518114851'));
        $this->assertTrue($this->validator->isValidIban('ES8401526659251705626017'));
    }

    #[Test]
    public function is_not_a_valid_iban()
    {
        $this->assertFalse($this->validator->isValidIban('ES8401526659151705626017'));
        $this->assertFalse($this->validator->isValidIban('ES5915221496277518114051'));
        $this->assertFalse($this->validator->isValidIban('ES06a3127 5991 1947 2388 4544'));
        $this->assertFalse($this->validator->isValidIban('ES88 0211 6656 9861 76818444 '));
        $this->assertFalse($this->validator->isValidIban('ES88'));
    }

    #[Test]
    public function is_a_valid_postal_code()
    {
        $this->assertTrue($this->validator->isValidPostalCode('41701'));
        $this->assertTrue($this->validator->isValidPostalCode('51101'));
        $this->assertTrue($this->validator->isValidPostalCode('01452'));
        $this->assertTrue($this->validator->isValidPostalCode('11450'));
        $this->assertTrue($this->validator->isValidPostalCode('28080'));
    }

    #[Test]
    public function is_not_a_valid_postal_code()
    {
        $this->assertFalse($this->validator->isValidPostalCode('55001'));
        $this->assertFalse($this->validator->isValidPostalCode('00111'));
        $this->assertFalse($this->validator->isValidPostalCode('410111'));
        $this->assertFalse($this->validator->isValidPostalCode(' 41700'));
        $this->assertFalse($this->validator->isValidPostalCode(null));
    }

    #[Test]
    public function is_a_valid_phone_number()
    {
        $this->assertTrue($this->validator->isValidPhone('677507711'));
        $this->assertTrue($this->validator->isValidPhone('954710011'));
        $this->assertTrue($this->validator->isValidPhone('856471476'));
        $this->assertTrue($this->validator->isValidPhone('714587450'));
        $this->assertTrue($this->validator->isValidPhone('900555666'));
    }

    #[Test]
    public function is_not_a_valid_phone_number()
    {
        $this->assertFalse($this->validator->isValidPhone('677-507-711'));
        $this->assertFalse($this->validator->isValidPhone('91474477'));
        $this->assertFalse($this->validator->isValidPhone('477507711'));
        $this->assertFalse($this->validator->isValidPhone(' 856471476 '));
        $this->assertFalse($this->validator->isValidPhone(null));
    }

    #[Test]
    public function is_a_valid_special_nif()
    {
        $this->assertTrue($this->validator->isValidSpecialNif('K0000000T'));
        $this->assertTrue($this->validator->isValidSpecialNif('K0000001R'));
        $this->assertTrue($this->validator->isValidSpecialNif('L0000000T'));
        $this->assertTrue($this->validator->isValidSpecialNif('L0000001R'));
        $this->assertTrue($this->validator->isValidSpecialNif('M0000000T'));
        $this->assertTrue($this->validator->isValidSpecialNif('M0000001R'));
    }

    #[Test]
    public function is_not_a_valid_special_nif()
    {
        $this->assertFalse($this->validator->isValidSpecialNif('K0000000A'));
        $this->assertFalse($this->validator->isValidSpecialNif('L0000001A'));
        $this->assertFalse($this->validator->isValidSpecialNif('M0000000Z'));
        $this->assertFalse($this->validator->isValidSpecialNif('X5487441D'));
        $this->assertFalse($this->validator->isValidSpecialNif('12345678Z'));
        $this->assertFalse($this->validator->isValidSpecialNif(null));
    }
}
