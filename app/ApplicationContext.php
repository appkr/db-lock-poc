<?php

namespace App;

use Myshop\Domain\Model\User;

class ApplicationContext
{
    private $dataContainer;

    public function __construct(array $data = [])
    {
        $this->dataContainer = $data;
    }

    // Getters & Setters

    public function isRunningInConsole(): bool
    {
        return $this->get('runningInConsole');
    }

    public function getAppEnv(): string
    {
        return $this->get('appEnv');
    }

    public function getAppVersion(): string
    {
        return $this->get('appVersion');
    }

    public function getTransactionId(): string
    {
        return $this->get('transactionId');
    }

    public function setTransactionId(string $newTransactionId)
    {
        $this->set('transactionId', $newTransactionId);
    }

    public function getTraceNumber(): int
    {
        return intval($this->get('traceNumber'));
    }

    public function setTraceNumber(int $newTraceNumber)
    {
        $this->set('traceNumber', $newTraceNumber);
    }

    public function increaseTraceNumber()
    {
        $this->set('traceNumber', intval($this->get('traceNumber')) + 1);
    }

    public function succeedPreviousContext(ApplicationContext $previousContext)
    {
        if ($previousContext === $this) {
            return;
        }
        $this->set('transactionId', $previousContext->getTransactionId());
        $this->set('traceNumber', $previousContext->getTraceNumber());
        $this->set('user', $previousContext->getUser());
    }

    public function getUser(): User
    {
        return $this->get('user');
    }

    public function getClientIp(): string
    {
        return $this->get('clientIp');
    }

    // Helpers

    public function all(): array
    {
        return $this->dataContainer;
    }

    public function keys(): array
    {
        return array_keys($this->dataContainer);
    }

    public function values(): array
    {
        return array_values($this->dataContainer);
    }
    
    public function add(array $parameters = [])
    {
        $this->dataContainer = array_replace($this->dataContainer, $parameters);
    }

    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->dataContainer) ? $this->dataContainer[$key] : $default;
    }

    private function set($key, $value)
    {
        $this->dataContainer[$key] = $value;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->dataContainer);
    }
}
