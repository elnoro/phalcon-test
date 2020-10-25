<?php

namespace Tests\Unit;

use App\Models\Contact;
use Phalcon\Validation\ValidatorInterface;

final class ContactTest extends AbstractUnitTest
{
    /**
     * @test
     */
    public function updatesTimestampsBeforeSaving(): void
    {
       $contact = new Contact();
       $this->assertNull($contact->getInsertedOn());
       $this->assertNull($contact->getUpdatedOn());

       $contact->beforeValidation();

       $this->assertNotNull($contact->getInsertedOn());
       $this->assertNotNull($contact->getUpdatedOn());
    }

    /**
     * @test
     */
    public function validatesPhoneNumbers(): void
    {
        $contact = new Contact();
        $contact->setFirstName('Test');
        $contact->setPhoneNumber('+1999999');

        $contact->validation();

        $this->assertTrue($contact->validation());

        $contact->setPhoneNumber('invalid');

        $this->assertFalse($contact->validation());
    }
}
