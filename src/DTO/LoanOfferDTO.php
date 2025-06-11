<?php

namespace App\DTO;

class LoanOfferDTO
{
    private $amount;
    private $duration;
    private $rate;
    private $partner;

    public function __construct($amount, $duration, $rate, $partner)
    {
        $this->amount = $amount;
        $this->duration = $duration;
        $this->rate = $rate;
        $this->partner = $partner;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getPartner()
    {
        return $this->partner;
    }
}
