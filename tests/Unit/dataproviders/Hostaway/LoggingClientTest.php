<?php

namespace Tests\Unit\dataproviders\Hostaway;

use App\DataProviders\Hostaway\HostawayClientInterface;
use App\DataProviders\Hostaway\LoggingClient;
use GuzzleHttp\Exception\TransferException;
use Phalcon\Cache\Exception\Exception as CacheException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class LoggingClientTest extends TestCase
{
    private LoggingClient $loggingClient;

    /** @var HostawayClientInterface|MockObject */
    private $hostawayClient;
    /** @var LoggerInterface|MockObject */
    private $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->hostawayClient = $this->createMock(HostawayClientInterface::class);
        $this->loggingClient = new LoggingClient($this->hostawayClient, $this->logger);
    }

    public static function errorDataProvider(): array
    {
        return [
            [new \RuntimeException("random message"), 'random message'],
            [new TransferException(), 'api'],
            [new CacheException(), 'cache'],
        ];
    }

    /**
     * @dataProvider errorDataProvider
     * @test
     *
     * @param \Throwable $exception
     * @param string $message
     */
    public function logsExceptionsInTimezones(\Throwable $exception, string $message): void
    {
        $this->hostawayClient
            ->method('getTimezones')
            ->willThrowException($exception);

        $this
            ->logger
            ->expects($this->once())
            ->method('error')->with($this->stringContains($message));

        $this->assertEmpty($this->loggingClient->getTimezones());
    }

    /**
     * @dataProvider errorDataProvider
     * @test
     *
     * @param \Throwable $exception
     * @param string $message
     */
    public function logsExceptionsInCountryCodes(\Throwable $exception, string $message): void
    {
        $this->hostawayClient
            ->method('getCountryCodes')
            ->willThrowException($exception);

        $this
            ->logger
            ->expects($this->once())
            ->method('error')->with($this->stringContains($message));

        $this->assertEmpty($this->loggingClient->getCountryCodes());
    }
}
