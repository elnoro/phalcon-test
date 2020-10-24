<?php
declare(strict_types=1);


use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ContactsController extends ControllerBase
{
    public function indexAction()
    {

    }


    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, 'Contacts', $_GET)->getParams();
        $parameters['order'] = "id";

        $contacts = Contact::find($parameters);
        if (count($contacts) == 0) {
            $this->flash->notice("The search did not find any contacts");

            $this->dispatcher->forward([
                "controller" => "contacts",
                "action" => "index",
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $contacts,
            'limit' => 10,
            'page' => $numberPage,
        ]);

        $this->view->page = $paginator->getPaginate();
    }


    public function newAction()
    {

    }


    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $contact = Contact::findFirstByid($id);
            if (!$contact) {
                $this->flash->error("contact was not found");

                $this->dispatcher->forward([
                    'controller' => "contacts",
                    'action' => 'index',
                ]);

                return;
            }

            $this->view->id = $contact->id;

            $this->tag->setDefault("id", $contact->id);
            $this->tag->setDefault("first_name", $contact->first_name);
            $this->tag->setDefault("last_name", $contact->last_name);
            $this->tag->setDefault("phone_number", $contact->phone_number);
            $this->tag->setDefault("country_code", $contact->country_code);
            $this->tag->setDefault("timezone", $contact->timezone);
            $this->tag->setDefault("inserted_on", $contact->inserted_on);
            $this->tag->setDefault("updated_on", $contact->updated_on);

        }
    }


    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "contacts",
                'action' => 'index',
            ]);

            return;
        }

        $contact = new Contact();
        $contact->firstName = $this->request->getPost("first_name", "int");
        $contact->lastName = $this->request->getPost("last_name", "int");
        $contact->phoneNumber = $this->request->getPost("phone_number", "int");
        $contact->countryCode = $this->request->getPost("country_code", "int");
        $contact->timezone = $this->request->getPost("timezone", "int");
        $contact->insertedOn = $this->request->getPost("inserted_on", "int");
        $contact->updatedOn = $this->request->getPost("updated_on", "int");


        if (!$contact->save()) {
            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "contacts",
                'action' => 'new',
            ]);

            return;
        }

        $this->flash->success("contact was created successfully");

        $this->dispatcher->forward([
            'controller' => "contacts",
            'action' => 'index',
        ]);
    }


    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "contacts",
                'action' => 'index',
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $contact = Contact::findFirstByid($id);

        if (!$contact) {
            $this->flash->error("contact does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "contacts",
                'action' => 'index',
            ]);

            return;
        }

        $contact->firstName = $this->request->getPost("first_name", "int");
        $contact->lastName = $this->request->getPost("last_name", "int");
        $contact->phoneNumber = $this->request->getPost("phone_number", "int");
        $contact->countryCode = $this->request->getPost("country_code", "int");
        $contact->timezone = $this->request->getPost("timezone", "int");
        $contact->insertedOn = $this->request->getPost("inserted_on", "int");
        $contact->updatedOn = $this->request->getPost("updated_on", "int");


        if (!$contact->save()) {

            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "contacts",
                'action' => 'edit',
                'params' => [$contact->id],
            ]);

            return;
        }

        $this->flash->success("contact was updated successfully");

        $this->dispatcher->forward([
            'controller' => "contacts",
            'action' => 'index',
        ]);
    }


    public function deleteAction($id)
    {
        $contact = Contact::findFirstByid($id);
        if (!$contact) {
            $this->flash->error("contact was not found");

            $this->dispatcher->forward([
                'controller' => "contacts",
                'action' => 'index',
            ]);

            return;
        }

        if (!$contact->delete()) {

            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "contacts",
                'action' => 'search',
            ]);

            return;
        }

        $this->flash->success("contact was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "contacts",
            'action' => "index",
        ]);
    }
}
