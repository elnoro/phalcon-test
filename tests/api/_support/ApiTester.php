<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */
    public function tryListingContacts(): void
    {
        $this->sendGet('/contacts');
    }

    public function tryAccessingContact(int $id): void
    {
        $this->sendGET(sprintf('/contacts/%d', $id));
    }

    public function tryCreatingContactFromData(array $data): void
    {
        $this->sendPOST('/contacts', json_encode($data));
    }

    public function tryUpdatingContactFromData(int $contactId, array $data)
    {
        $this->sendPUT(sprintf('/contacts/%d', $contactId), json_encode($data));
    }

    public function createContactFromData(array $data): int
    {
        $this->tryCreatingContactFromData($data);

        $this->seeResponseCodeIsSuccessful();
        $this->seeResponseJsonMatchesJsonPath('$.id');

        return $this->grabDataFromResponseByJsonPath('$.id')[0];
    }

    public function createContact(): int
    {
        return $this->createContactFromData([
            'firstName' => 'Example',
            'phoneNumber' => '+1'.time(),
        ]);
    }
}
