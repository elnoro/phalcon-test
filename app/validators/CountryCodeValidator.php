<?php
declare(strict_types=1);

namespace App\Validators;

use Phalcon\Messages\Message;
use Phalcon\Validation\AbstractValidator;

final class CountryCodeValidator extends AbstractValidator
{
    public function validate(\Phalcon\Validation $validation, $field): bool
    {
        /** @var CountryCodeCheckerInterface $timezoneChecker */
        $countryCodeChecker = $validation->getDI()->get('hostaway_existence_checker');

        $value = $validation->getValue($field);

        if (null !== $value && !$countryCodeChecker->countryCodeExists($value)) {
            $message = $this->getOption('message');

            if (!$message) {
                $message = 'Invalid country code. Get the list of available country codes at api.hostaway.com';
            }

            $validation->appendMessage(new Message($message, $field, 'CountryCode'));

            return false;
        }

        return true;
    }

}