<?php

use Codeception\Example;

class ValidationCest
{
    // TODO remove duplication
    public const EXPECTED_US_NUMBER = '+19991234567';

    protected function incompleteData(): array
    {
        return [
            ['data' => []],
            ['data' => ['firstName' => 'Test']],
            ['data' => ['phoneNumber' => self::EXPECTED_US_NUMBER]],
            ['data' => ['firstName' => '', 'phoneNumber' => self::EXPECTED_US_NUMBER]],
            ['data' => ['firstName' => 'Test', 'phoneNumber' => '']],
        ];
    }

    protected function invalidPhones(): array
    {
        return [
            ['phoneNumber' => ''],
            ['phoneNumber' => 'not a number'],
            ['phoneNumber' => '1'],
            ['phoneNumber' => '11111111'],
            ['phoneNumber' => '+'.str_repeat('2', 30)],
        ];
    }

    protected function countryCodesValidation(): array
    {
        return [
            ['countryCode' => 'AF', 'expectedHttpCode' => 200],
            ['countryCode' => 'US', 'expectedHttpCode' => 200],
            ['countryCode' => '', 'expectedHttpCode' => 400],
            ['countryCode' => 'invalid', 'expectedHttpCode' => 400],
            ['countryCode' => '不気味', 'expectedHttpCode' => 400],
        ];
    }

    protected function timezonesValidation(): array
    {
        return [
            ['timezone' => 'Antarctica/Casey', 'expectedHttpCode' => 200],
            ['timezone' => 'Asia/Aqtobe', 'expectedHttpCode' => 200],
            ['timezone' => '', 'expectedHttpCode' => 400],
            ['timezone' => 'invalid', 'expectedHttpCode' => 400],
            ['timezone' => '不気味', 'expectedHttpCode' => 400],
        ];
    }

    /**
     * @dataProvider incompleteData
     *
     * @param ApiTester $I
     * @param Example $example
     */
    public function tryToOmitRequiredFields(ApiTester $I, Example $example)
    {
        $I->tryCreatingContactFromData($example['data']);

        $I->seeResponseCodeIs(400);
    }

    /**
     * @dataProvider invalidPhones
     *
     * @param ApiTester $I
     * @param Example $example
     */
    public function tryToSubmitInvalidPhones(ApiTester $I, Example $example)
    {
        $contactId = $I->createContact();
        $I->tryUpdatingContactFromData($contactId, ['phoneNumber' => $example['phoneNumber']]);

        $I->seeResponseCodeIs(400);
    }

    /**
     * @dataProvider countryCodesValidation
     *
     * @param ApiTester $I
     * @param Example $example
     */
    public function validatesCountryCodes(ApiTester $I, Example $example)
    {
        $contactId = $I->createContact();
        $I->tryUpdatingContactFromData($contactId, ['countryCode' => $example['countryCode']]);

        $I->seeResponseCodeIs($example['expectedHttpCode']);
    }


    /**
     * @dataProvider timezonesValidation
     *
     * @param ApiTester $I
     * @param Example $example
     */
    public function validatesTimezones(ApiTester $I, Example $example)
    {
        $contactId = $I->createContact();
        $I->tryUpdatingContactFromData($contactId, ['timezone' => $example['timezone']]);

        $I->seeResponseCodeIs($example['expectedHttpCode']);
    }
}
