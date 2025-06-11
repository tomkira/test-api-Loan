<?php

namespace App\Service;

use App\DTO\LoanApplicationDTO;
use App\DTO\LoanOfferDTO;
use Symfony\Component\HttpKernel\KernelInterface;

class LoanOfferService
{
    private string $storagePath;

    public function __construct(KernelInterface $kernel)
    {
        $this->storagePath = $kernel->getProjectDir().'/storage/';
    }

    /**
     * Recherche et retourne les offres partenaires selon le modèle de demande.
     */
    public function searchOffers(LoanApplicationDTO $model): array
    {
        $offers = array_merge(
            $this->getOffersFromJson(
                $this->storagePath.'BNP.json',
                $model->getAmount(), $model->getDuration(), 'montant', 'duree', 'taux', 'BNP'
            ),
            $this->getOffersFromJson(
                $this->storagePath.'CARREFOURBANK.json',
                $model->getAmount(), $model->getDuration(), 'montant_pret', 'duree_pret', 'taux_pret', 'CARREFOURBANK'
            ),
            $this->getOffersFromJson(
                $this->storagePath.'SG.json',
                $model->getAmount(), $model->getDuration(), 'amount', 'duration', 'rate', 'SG'
            )
        );

        // Tri par taux croissant
        usort(
            $offers, function ($a, $b) {
                return $a->getRate() <=> $b->getRate();
            }
        );

        return $this->formatOffers($offers);
    }

    /**
     * Récupère et normalise les offres partenaires selon le montant et la durée demandés.
     */
    private function getOffersFromJson($filepath, $amount, $duration, $amountKey, $durationKey, $rateKey, $partner)
    {
        if (!file_exists($filepath)) {
            return [];
        }
        $json = file_get_contents($filepath);
        // Nettoyage du JSON mal formé :
        // 1. Supprimer les commentaires sur une ligne
        $json = preg_replace('!//.*!', '', $json);
        // 2. Supprimer les commentaires multi-lignes
        $json = preg_replace('!/\*.*?\*/!s', '', $json);
        // 3. Supprimer les virgules en trop avant ] ou }
        $json = preg_replace('/,\s*([\]}])/', '$1', $json);
        // 4. Trim
        $json = trim($json);
        $data = json_decode($json, true);

        $offers = [];
        if (!is_array($data)) {
            return [];
        }

        foreach ($data as $offer) {
            if (isset($offer[$amountKey], $offer[$durationKey], $offer[$rateKey])
                && $offer[$amountKey] == $amount
                && $offer[$durationKey] == $duration
            ) {
                $offers[] = new LoanOfferDTO(
                    $offer[$amountKey],
                    $offer[$durationKey],
                    $offer[$rateKey],
                    $partner
                );
            }
        }

        return $offers;
    }

    /**
     * Formate un tableau d'offres LoanOfferDTO en tableau associatif.
     */
    public function formatOffers(array $offers): array
    {
        return array_map(function ($offer) {
            return [
                'amount' => $offer->getAmount(),
                'duration' => $offer->getDuration(),
                'rate' => $offer->getRate(),
                'partner' => $offer->getPartner(),
            ];
        }, $offers);
    }
}
