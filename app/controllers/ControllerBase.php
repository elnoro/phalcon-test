<?php
declare(strict_types=1);

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    final protected function sendJson(array $data): void
    {
        $this
            ->response
            ->setStatusCode(200)
            ->setJsonContent($data)
            ->send();
    }

    final protected function noContent(): void
    {
        $this->sendCode(204);
    }


    final protected function notFound(): void
    {
        $this->sendCode(404);
    }

    private function sendCode(int $code): void
    {
        $this
            ->response
            ->setStatusCode($code)
            ->send();
    }
}
