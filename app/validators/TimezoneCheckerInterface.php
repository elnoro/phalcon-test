<?php
declare(strict_types=1);

namespace App\Validators;

interface TimezoneCheckerInterface
{
    public function timezoneExists(string $timezone): bool;
}