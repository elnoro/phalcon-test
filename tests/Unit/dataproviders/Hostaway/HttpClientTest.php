<?php

namespace Tests\Unit\dataproviders;

use App\DataProviders\Hostaway\HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Unit\AbstractUnitTest;

final class HttpClientTest extends AbstractUnitTest
{
    private const EXPECTED_COUNTRY_RESPONSE = '{"status":"success","result":{"AF":"Afghanistan"}}';
    private const EXPECTED_TIMEZONE_RESPONSE = '{"status":"success","result":{"Pacific\/Midway": { "value": "(UTC -11:00) Pacific\/Midway", "diff": "-11:00"}}}';
    /**
     * @test
     */
    public function canInstantiateItself(): void
    {
        $result = HttpClient::createFromSettings('test');

        $this->assertInstanceOf(HttpClient::class, $result);
    }

    /**
     * @test
     */
    public function parsesDataForAndTimezones(): void
    {
        $mockHandler = new MockHandler([
            new Response(200, [], self::EXPECTED_COUNTRY_RESPONSE),
            new Response(200, [], self::EXPECTED_TIMEZONE_RESPONSE),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $guzzle = new Client(['handler' => $handlerStack]);

        $httpClient = new HttpClient($guzzle);


        $countryCodes = $httpClient->getCountryCodes();

        $this->assertCount(1, $countryCodes);
        $this->assertEquals('AF', $countryCodes[0]);

        $timezones = $httpClient->getTimezones();

        $this->assertCount(1, $timezones);
        $this->assertEquals('Pacific/Midway', $timezones[0]);
    }
}
