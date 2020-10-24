<?php
declare(strict_types=1);

use Phalcon\Http\Request;
use Phalcon\Http\Response;

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

            $this->sendJson([
                'firstName' => $contact->first_name,
                'phoneNumber' => $contact->phone_number,
            ]);

            return;
        }
        if (true === $this->request->isPost()) {

            // TODO can probably use getRawJsonBody + object normalizer to make this simpler
            $requestData = json_decode($this->request->getRawBody(), true);
            $contact = new Contact();
            $contact->first_name = $requestData['firstName'];
            $contact->phone_number = $requestData['phoneNumber'];
            if (!$contact->save()) {
                $this->sendJson([
                    'error' => 'invalid_contact',
                    'details' => $contact->getMessages(),
                ]);
                
                return;
            }

            $responseData = $requestData;
            // TODO use ObjectNormalizer instead of manual decoding
            $responseData['id'] = $contact->id;

            $this->sendJson($responseData);

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
}