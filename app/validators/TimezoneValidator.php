<?php
declare(strict_types=1);

namespace App\Validators;

use Phalcon\Messages\Message;
use Phalcon\Validation\AbstractValidator;

final class TimezoneValidator extends AbstractValidator
{
    public function validate(\Phalcon\Validation $validator, $field): bool
    {
        /** @var TimezoneCheckerInterface $timezoneChecker */
        $timezoneChecker = $validator->getDI()->get('hostaway_existence_checker');

        $value = $validator->getValue($field);
        if (null !== $value && !$timezoneChecker->timezoneExists($value)) {
            $message = $this->getOption('message');

            if (!$message) {
                $message = 'Invalid timezone. Get the list of available timezones at api.hostaway.com';
            }

            $validator->appendMessage(new Message($message, $field, 'Timezone'));

            return false;
        }

        return true;
    }

}