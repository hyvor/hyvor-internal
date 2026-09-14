<?php

namespace Hyvor\Internal\Bundle\Comms\Event\ToCore\Billing;

readonly class CreateSubscriptionResponse
{

    public function __construct(
        private bool $success,
        private ?int $subscriptionId,
        private ?string $errorMessage,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getSubscriptionId(): ?int
    {
        return $this->subscriptionId;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

}
