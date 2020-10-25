<?php

class SearchCest
{
    public const EXPECTED_US_NUMBER = '+19991234567';

    public function trySearchingByName(ApiTester $I): void
    {
        $uniqueJohn = uniqid(true);
        $uniqueMary = uniqid(true);
        $sharedLastName = uniqid('Smith-');

        $I->createContactFromData([
            'firstName' => 'John ' . $uniqueJohn,
            'lastName' => $sharedLastName,
            'phoneNumber' => self::EXPECTED_US_NUMBER,
        ]);
        $I->createContactFromData([
            'firstName' => 'Mary' . $uniqueMary,
            'lastName' => $sharedLastName,
            'phoneNumber' => self::EXPECTED_US_NUMBER,
        ]);

        // expecting only John
        $I->sendGet('/contacts', ['name' => $uniqueJohn]);

        $I->seeResponseContains($uniqueJohn);
        $I->dontSeeResponseContains($uniqueMary);

        // expecting only Mary
        $I->sendGet('/contacts', ['name' => $uniqueMary]);

        $I->seeResponseContains($uniqueMary);
        $I->dontSeeResponseContains($uniqueJohn);

        // expecting both
        $I->sendGet('/contacts', ['name' => $sharedLastName]);

        $I->seeResponseContains($uniqueMary);
        $I->seeResponseContains($uniqueJohn);
    }

    public function searchByFullName(ApiTester $I): void
    {
        $uniqueFirstNamePostFix = uniqid(true);
        $uniqueLastNamePrefix = uniqid(true);

        $I->createContactFromData([
            'firstName' => 'John ' . $uniqueFirstNamePostFix,
            'lastName' => $uniqueLastNamePrefix . ' Smith',
            'phoneNumber' => self::EXPECTED_US_NUMBER,
        ]);
    }
}
