<?php
declare(strict_types=1);

use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @property Request $request
 * @property Response $response
 */
class ApiController extends ControllerBase
{
    // TODO split actions by routes
    public function contactsAction(int $contactId = null)
    {
        if ($contactId) {
            $contact = Contact::findFirst(['id' => $contactId]);
            $this->sendJson($contact->toArray());

            return;
        }
        if (true === $this->request->isPost()) {
            $contact = new Contact();
            $this->parseRequestInto($contact);

            if (!$contact->save()) {
                $this->sendJson([
                    'error' => 'invalid_contact',
                    'details' => $contact->getMessages(),
                ]);

                return;
            }

            $this->sendJson($contact->toArray());

            return;
        }

        $this->search();
    }

    private function search(): void
    {
        $this->sendJson(['ok']);
    }

    private function sendJson(array $data): void
    {
        $this
            ->response
            ->setStatusCode(200, 'OK')
            ->setJsonContent($data)
            ->send();
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
}