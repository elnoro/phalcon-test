<?php
declare(strict_types=1);

namespace App\DataProviders\Hostaway;

use Phalcon\Cache;
use Psr\SimpleCache\CacheInterface;

final class CachingClient implements HostawayClientInterface
{
    private const DEFAULT_TTL = 24 * 60 * 60;
    private const COUNTRY_CODES_KEY = 'hostaway_country_codes';
    private const TIMEZONES_KEY = 'hostaway_timezones';

    private HostawayClientInterface $hostawayClient;
    private CacheInterface $cache;
    private int $ttl;

    public function __construct(
        HostawayClientInterface $hostawayClient,
        CacheInterface $cache,
        int $ttl = self::DEFAULT_TTL
    )
    {
        $this->hostawayClient = $hostawayClient;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }


    public function getCountryCodes(): iterable
    {
        if ($cachedResult = $this->cache->get(self::COUNTRY_CODES_KEY)) {
            return $cachedResult;
        }

        $result = $this->hostawayClient->getCountryCodes();
        $this->cache->set(self::COUNTRY_CODES_KEY, $result, $this->ttl);

        return $result;
    }

    public function getTimezones(): iterable
    {
        if ($cachedResult = $this->cache->get(self::TIMEZONES_KEY)) {
            return $cachedResult;
        }

        $result = $this->hostawayClient->getTimezones();
        $this->cache->set(self::TIMEZONES_KEY, $result, $this->ttl);

        return $result;
    }
}