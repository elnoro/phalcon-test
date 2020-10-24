<?php

class ApiCest
{
    private const EXPECTED_PHONE_NUMBER = '+19991234567';

    // TODO handle 404
    const EXPECTED_FIRST_NAME = 'expected_first_name';

    public function tryListingContacts(ApiTester $I): void
    {
        $I->sendGet('/contacts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryCreatingMinimalContact(ApiTester $I): void
    {
        $contactData = [
            'firstName' => self::EXPECTED_FIRST_NAME,
            'phoneNumber' => self::EXPECTED_PHONE_NUMBER,
        ];
        $I->sendPOST('/contacts', json_encode($contactData));

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContains(self::EXPECTED_FIRST_NAME);
        $I->seeResponseContains(self::EXPECTED_PHONE_NUMBER);
        $I->seeResponseJsonMatchesJsonPath('$.id');

        $newContactId = $I->grabDataFromResponseByJsonPath('$.id')[0];

        $I->sendGET(sprintf('/contacts/%d', $newContactId));
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContains(self::EXPECTED_FIRST_NAME);
        $I->seeResponseContains(self::EXPECTED_PHONE_NUMBER);
        $I->seeResponseJsonMatchesJsonPath('$.id');
    }
}