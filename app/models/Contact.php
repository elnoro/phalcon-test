<?php

class Contact extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $phone_number;

    /**
     *
     * @var string
     */
    public $country_code;

    /**
     *
     * @var string
     */
    public $timezone;

    /**
     *
     * @var string
     */
    public $inserted_on;

    /**
     *
     * @var string
     */
    public $updated_on;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phonebook");
        $this->setSource("contacts");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contact[]|Contact|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contact|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function beforeValidation()
    {
        $dateTime = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        if (null === $this->inserted_on) {
            $this->inserted_on = $dateTime;
        }
        $this->updated_on = $dateTime;
    }
}
