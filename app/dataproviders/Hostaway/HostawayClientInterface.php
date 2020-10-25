<?php
declare(strict_types=1);

namespace App\DataProviders\Hostaway;

interface HostawayClientInterface
{
    /**
     * @return iterable|string[]
     */
    public function getCountryCodes(): iterable;

    /**
     * @return iterable|string[]
     */
    public function getTimezones(): iterable;
}