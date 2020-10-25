<?php

class ApiCest
{
    private const EXPECTED_US_NUMBER = '+19991234567';

    const EXPECTED_FIRST_NAME = 'expected_first_name';
    const EXPECTED_UK_NUMBER = '+44999123456';

    public function tryListingContacts(ApiTester $I): void
    {
        $I->tryListingContacts();
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryCreatingMinimalContact(ApiTester $I): void
    {
        $contactData = [
            'firstName' => self::EXPECTED_FIRST_NAME,
            'phoneNumber' => self::EXPECTED_US_NUMBER,
        ];

        $newContactId = $I->createContactFromData($contactData);

        $I->seeResponseContains(self::EXPECTED_FIRST_NAME);
        $I->seeResponseContains(self::EXPECTED_US_NUMBER);

        $I->tryAccessingContact($newContactId);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContains(self::EXPECTED_FIRST_NAME);
        $I->seeResponseContains(self::EXPECTED_US_NUMBER);
        $I->seeResponseContainsJson(['id' => $newContactId]);
    }

    public function tryUpdatingContact(ApiTester $I): void
    {
        $contactId = $I->createContact();

        $I->sendPUT(sprintf('/contacts/%d', $contactId), json_encode([
            'firstName' => 'new_first_name',
            'lastName' => 'expected_last_name',
            'phoneNumber' => self::EXPECTED_UK_NUMBER,
            'country_code' => 'US',
            'timezone' => 'UTC',
        ]));

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContains('expected_last_name');
        $I->seeResponseContains(self::EXPECTED_UK_NUMBER);
    }

    public function tryAccessingNonExistentContact(ApiTester $I): void
    {
        $I->tryAccessingContact(PHP_INT_MAX);
        $I->seeResponseCodeIs(404);
    }

    public function tryDeletingContact(ApiTester $I): void
    {
        $contactId = $I->createContact();

        $I->sendDELETE(sprintf('/contacts/%d', $contactId));
        $I->seeResponseCodeIsSuccessful();

        $I->tryAccessingContact($contactId);

        $I->seeResponseCodeIs(404);
    }
}