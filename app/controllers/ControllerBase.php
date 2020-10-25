<?php
declare(strict_types=1);

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    final protected function sendJson(array $data, int $code = 200): void
    {
        $this
            ->response
            ->setStatusCode($code)
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
