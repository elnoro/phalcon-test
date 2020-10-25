<?php

namespace App\Models;

use App\DataProviders\Hostaway\HostawayClientInterface;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Callback;

class Contact extends \Phalcon\Mvc\Model
{
    private ?int $id = null;

    private string $first_name = '';

    private ?string $last_name = null;

    private string $phone_number = '';

    private ?string $country_code = null;

    private ?string $timezone = null;

    private ?string $inserted_on = null;

    private ?string $updated_on = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phonebook");
        $this->setSource("contacts");
    }

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'phoneNumber',
            new Regex(['message' => 'The phone number is required', 'pattern' => '/^\+\d{5,20}$/'])
        );

        $validator->add(
            'countryCode',
            new Callback([
                'callback' => function ($data) {
                    $submittedCountryCode = $data->getCountryCode();
                    if (null === $submittedCountryCode) {
                        return true;
                    }

                    /** @var HostawayClientInterface $hostaway */
                    $hostaway = $this->getDI()->get('hostawayClient');

                    foreach ($hostaway->getCountryCodes() as $countryCode) {
                        if ($submittedCountryCode === $countryCode) {
                            return true;
                        }
                    }

                    return false;
                }
            ])
        );

        $validator->add(
            'timezone',
            new Callback([
                'callback' => function ($data) {
                    $submittedTimezone = $data->getTimezone();
                    if (null === $submittedTimezone) {
                        return true;
                    }

                    /** @var HostawayClientInterface $hostaway */
                    $hostaway = $this->getDI()->get('hostawayClient');

                    foreach ($hostaway->getTimezones() as $timezone) {
                        if ($submittedTimezone === $timezone) {
                            return true;
                        }
                    }

                    return false;
                }
            ])
        );

        return $this->validate($validator);
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string|null $last_name
     */
    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * @param string $phone_number
     */
    public function setPhoneNumber(string $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    /**
     * @param string|null $country_code
     */
    public function setCountryCode(?string $country_code): void
    {
        $this->country_code = $country_code;
    }

    /**
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string|null $timezone
     */
    public function setTimezone(?string $timezone): void
    {
        $this->timezone = $timezone;
    }

    /**
     * @return string|null
     */
    public function getInsertedOn(): ?string
    {
        return $this->inserted_on;
    }

    /**
     * @return string|null
     */
    public function getUpdatedOn(): ?string
    {
        return $this->updated_on;
    }
}
