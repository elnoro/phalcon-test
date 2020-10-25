<?php
declare(strict_types=1);

namespace App\DataProviders\Hostaway;

use App\Validators\CountryCodeCheckerInterface;
use App\Validators\TimezoneCheckerInterface;

final class ExistenceChecker implements TimezoneCheckerInterface, CountryCodeCheckerInterface
{
    private HostawayClientInterface $hostawayClient;

    public function __construct(HostawayClientInterface $hostawayClient)
    {
        $this->hostawayClient = $hostawayClient;
    }

    public function countryCodeExists(string $countryCode): bool
    {
        foreach ($this->hostawayClient->getCountryCodes() as $hostawayCountryCode) {
            if ($countryCode === $hostawayCountryCode) {
                return true;
            }
        }

        return false;
    }

    public function timezoneExists(string $timezone): bool
    {
        foreach ($this->hostawayClient->getTimezones() as $hostawayTimezone) {
            if ($timezone === $hostawayTimezone) {
                return true;
            }
        }

        return false;
    }
}