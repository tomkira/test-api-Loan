<?php

namespace App\Controller\Api;

use App\Response\ApiJsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoanParameterController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/amounts', name: 'api_amounts', methods: ['GET'])]
    public function getAmounts(): ApiJsonResponse
    {
        $this->logger->info('get amounts'.__CLASS__);
        try {
            $amounts = [50000, 100000, 200000, 500000];

            return new ApiJsonResponse(['amounts' => $amounts], 200, 'Liste des montants');
        } catch (\Throwable $e) {
            $this->logger->error('Erreur lors de get amounts: '.$e->getMessage());

            return new ApiJsonResponse(null, 500, 'Erreur interne serveur: '.$e->getMessage());
        }
    }

    #[Route('/durations', name: 'api_durations', methods: ['GET'])]
    public function getDurations(): ApiJsonResponse
    {
        $this->logger->info('get durations'.__CLASS__);
        try {
            $durations = [15, 20, 25];

            return new ApiJsonResponse(['durations' => $durations], 200, 'Liste des durées');
        } catch (\Throwable $e) {
            $this->logger->error('Erreur lors de get durations: '.$e->getMessage());

            return new ApiJsonResponse(null, 500, 'Erreur interne serveur: '.$e->getMessage());
        }
    }
}
