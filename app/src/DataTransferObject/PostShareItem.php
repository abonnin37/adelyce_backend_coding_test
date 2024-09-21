<?php

namespace App\DataTransferObject;


use Symfony\Component\Validator\Constraints as Assert;

class PostShareItem
{
    #[Assert\Positive]
    #[Assert\NotBlank]
    private ?int $userId = null;

    #[Assert\Positive]
    #[Assert\NotBlank]
    private ?int $itemId = null;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): void
    {
        $this->itemId = $itemId;
    }
}