<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LoanApplicationDTO
{
    #[Assert\NotBlank]
    #[Assert\Choice([50000, 100000, 200000, 500000])]
    private $amount;

    #[Assert\NotBlank]
    #[Assert\Choice([15, 20, 25])]
    private $duration;

    #[Assert\NotBlank]
    private $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    private $email;

    #[Assert\NotBlank]
    private $phone;

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public static function fromData(array $array): self
    {
        $dto = new self();
        $dto->setAmount($array['amount'] ?? 0);
        $dto->setDuration($array['duration'] ?? 0);
        $dto->setName($array['name'] ?? '');
        $dto->setEmail($array['email'] ?? '');
        $dto->setPhone($array['phone'] ?? '');

        return $dto;
    }
}
