<?php

namespace Tests\Unit\dataproviders\Hostaway;

use App\DataProviders\Hostaway\CachingClient;
use App\DataProviders\Hostaway\HostawayClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

final class CachingClientTest extends TestCase
{
    private const EXPECTED_TIMEZONE = ['expected_timezone'];
    private const EXPECTED_COUNTRY_CODE = ['expected_country_code'];

    private const CACHED_CODE = ['cached code'];
    private const CACHED_TIMEZONE = ['cached timezone'];

    /** @var CachingClient */
    private CachingClient $cachingClient;

    /** @var HostawayClientInterface|MockObject */
    private $hostawayClient;
    /** @var CacheInterface|MockObject */
    private $cache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hostawayClient = $this->createMock(HostawayClientInterface::class);
        $this->cache = $this->createMock(CacheInterface::class);

        $this->cachingClient = new CachingClient($this->hostawayClient, $this->cache);
    }

    public static function methodsDataProvider(): array
    {
        return [
            ['getCountryCodes', 'hostaway_country_codes', self::CACHED_CODE],
            ['getTimezones', 'hostaway_timezones', self::CACHED_TIMEZONE],
        ];
    }

    /**
     * @dataProvider methodsDataProvider
     * @test
     */
    public function returnsDataFromCache(string $method, string $cacheKey, array $expectedCachedValue): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn($expectedCachedValue);

        $this->hostawayClient
            ->expects($this->never())
            ->method($method);

        $this->assertEquals($expectedCachedValue, $this->cachingClient->$method());
    }

    /**
     * @dataProvider methodsDataProvider
     * @test
     */
    public function updatesCache(string $method, string $cacheKey, array $expectedCachedValue): void
    {
        $this->cache
            ->method('get')
            ->with($cacheKey)
            ->willReturn(null);

        $this->hostawayClient
            ->expects($this->once())
            ->method($method)
            ->willReturn($expectedCachedValue);

        $this->cache
            ->expects($this->once())
            ->method('set')
            ->with($cacheKey, $expectedCachedValue, $this->greaterThan(0));

        $this->assertEquals($expectedCachedValue, $this->cachingClient->$method());
    }

    /**
     * @test
     */
    public function requestsTheRealDataWhenCacheIsEmpty(): void
    {
        $this->hostawayClient
            ->expects($this->once())
            ->method('getCountryCodes')
            ->willReturn(self::EXPECTED_COUNTRY_CODE);

        $this->assertEquals(self::EXPECTED_COUNTRY_CODE, $this->cachingClient->getCountryCodes());

        $this->hostawayClient
            ->expects($this->once())
            ->method('getTimezones')
            ->willReturn(self::EXPECTED_TIMEZONE);

        $this->assertEquals(self::EXPECTED_TIMEZONE, $this->cachingClient->getTimezones());
    }

}
