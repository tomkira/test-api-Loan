<?php

declare(strict_types=1);

namespace App\DTO;

class LoanOfferDTO
{
    private int $amount;
    private int $duration;
    private float $rate;
    private string $partner;

    public function __construct(int $amount, int $duration, float $rate, string $partner)
    {
        $this->amount = $amount;
        $this->duration = $duration;
        $this->rate = $rate;
        $this->partner = $partner;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getPartner(): string
    {
        return $this->partner;
    }
}
