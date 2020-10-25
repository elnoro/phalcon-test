<?php
declare(strict_types=1);

namespace App\DataProviders\Hostaway;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Utils;
use Phalcon\Logger;
use Psr\Log\LoggerInterface;

final class HttpClient implements HostawayClientInterface
{
    private Client $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public static function createFromSettings(string $apiUrl, LoggerInterface $logger): self
    {
        $loggingMiddleware = Middleware::log(
            $logger,
            new MessageFormatter(MessageFormatter::SHORT),
            (string)Logger::INFO,
        );

        $handler = new HandlerStack();
        $handler->setHandler(Utils::chooseHandler());
        $handler->push($loggingMiddleware);

        $guzzle = new Client([
            'base_uri' => $apiUrl,
            'handler' => $handler,
        ]);

        return new self($guzzle);
    }

    public function getCountryCodes(): iterable
    {
        return $this->parseData('/countries');
    }

    public function getTimezones(): iterable
    {
        return $this->parseData('/timezones');
    }

    /**
     * @param string $str
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function parseData(string $str): array
    {
        $response = $this->guzzle->get('' . $str)->getBody();

        $result = json_decode((string)$response, true, 512, JSON_THROW_ON_ERROR);
        if (!isset($result['result'])) {
            throw new \RuntimeException('Unexpected format: no result field');
        }

        return array_keys($result['result']);
    }
}