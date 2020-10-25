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
}
