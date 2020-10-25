<?php
declare(strict_types=1);

namespace App\DataProviders\Hostaway;

use Psr\SimpleCache\CacheException;
use GuzzleHttp\Exception\TransferException;
use Psr\Log\LoggerInterface;

final class LoggingClient implements HostawayClientInterface
{
    private HostawayClientInterface $hostawayClient;
    private LoggerInterface $logger;

    public function __construct(
        HostawayClientInterface $hostawayClient,
        LoggerInterface $logger
    ) {
        $this->hostawayClient = $hostawayClient;
        $this->logger = $logger;
    }

    public function getCountryCodes(): iterable
    {
        try {
            return $this->hostawayClient->getCountryCodes();
        } catch (CacheException $exception) {
            $this->logger->error(sprintf('[Hostaway] Got cache error: %s', $exception->getMessage()));
        } catch (TransferException $exception) {
            $this->logger->error(sprintf('[Hostaway] Got api error: %d %s', $exception->getCode(), $exception->getMessage()));
        } catch (\Throwable $exception) {
            $this->logger->error(sprintf('[Hostaway] Got error: %s', $exception->getMessage()));
        }

        return [];
    }

    public function getTimezones(): iterable
    {
        try {
            return $this->hostawayClient->getTimezones();
        } catch (CacheException $exception) {
            $this->logger->error(sprintf('[Hostaway] Got cache error: %s', $exception->getMessage()));
        } catch (TransferException $exception) {
            $this->logger->error(sprintf('[Hostaway] Got api error: %d %s', $exception->getCode(), $exception->getMessage()));
        } catch (\Throwable $exception) {
            $this->logger->error(sprintf('[Hostaway] Got error: %s', $exception->getMessage()));
        }

        return [];
    }


}