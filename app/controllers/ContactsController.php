<?php
declare(strict_types=1);

use App\Models\Contact;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @property Request $request
 * @property Response $response
 */
class ContactsController extends ControllerBase
{
    public function createAction(): void
    {
        $contact = new Contact();
        $this->parseRequestInto($contact);

        if (!$contact->save()) {
            $this->sendJson([
                'error' => 'invalid_contact',
                'details' => $contact->getMessages(),
            ], 400);

            return;
        }

        $this->sendJson($contact->toArray());
    }

    public function updateAction(int $contactId): void
    {
        $contact = $this->findContact($contactId);
        if (null === $contact) {
            return;
        }

        $this->parseRequestInto($contact);

        if (!$contact->save()) {
            $this->sendJson([
                'error' => 'invalid_contact',
                'details' => $contact->getMessages(),
            ], 400);

            return;
        }

        $this->sendJson($contact->toArray());
    }

    public function getAction(int $contactId): void
    {
        $contact = $this->findContact($contactId);
        if (null === $contact) {
            return;
        }

        $this->sendJson($contact->toArray());
    }

    public function deleteAction(int $contactId): void
    {
        $contact = $this->findContact($contactId);
        if (null === $contact) {
            return;
        }
        $contact->delete();

        $this->noContent();
    }

    public function listAction(): void
    {
        $namePart = $this->request->get('name');
        $foundContacts = $namePart ? Contact::findByNameSubstring($namePart) : Contact::find();

        $this->sendJson($foundContacts->toArray());
    }

    private function parseRequestInto(Contact $contact): void
    {
        $objectNormalizer = new ObjectNormalizer();
        $requestData = $this->request->getJsonRawBody(true);

        // not using assign because not sure how secure it is
        $objectNormalizer->denormalize(
            $requestData,
            Contact::class,
            null,
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => $contact,
                AbstractNormalizer::ATTRIBUTES => ['firstName', 'lastName', 'phoneNumber', 'countryCode', 'timezone']],
        );
    }

    private function findContact(int $contactId): ?Contact
    {
        $contact = Contact::findFirst($contactId) ?? null;
        if (null === $contact) {
            $this->notFound();
        }

        return $contact;
    }
}