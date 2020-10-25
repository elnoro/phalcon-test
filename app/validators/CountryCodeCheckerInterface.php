<?php

namespace App\Validators;

interface CountryCodeCheckerInterface
{
    public function countryCodeExists(string $countryCode): bool;
}