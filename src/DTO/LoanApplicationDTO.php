<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LoanApplicationDTO
{
    #[Assert\NotBlank]
    #[Assert\Choice([50000, 100000, 200000, 500000])]
    private int $amount;

    #[Assert\NotBlank]
    #[Assert\Choice([15, 20, 25])]
    private int $duration;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    private string $phone;

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

    /**
     * @param array<string, mixed> $array
     */
    public static function fromData(array $array): self
    {
        $dto = new self();
        $dto->setAmount((int) ($array['amount'] ?? 0));
        $dto->setDuration((int) ($array['duration'] ?? 0));
        $dto->setName((string) ($array['name'] ?? ''));
        $dto->setEmail((string) ($array['email'] ?? ''));
        $dto->setPhone((string) ($array['phone'] ?? ''));

        return $dto;
    }
}
