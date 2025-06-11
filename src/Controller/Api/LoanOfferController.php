<?php

namespace App\Controller\Api;

use App\DTO\LoanApplicationDTO;
use App\Response\ApiJsonResponse;
use App\Service\LoanOfferService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoanOfferController extends AbstractController
{
    private LoanOfferService $loanOfferService;
    private LoggerInterface $logger;

    public function __construct(LoanOfferService $loanOfferService, LoggerInterface $logger)
    {
        $this->loanOfferService = $loanOfferService;
        $this->logger = $logger;
    }

    #[Route('/loan-offers/search', name: 'loan_offer_search', methods: ['POST'])]
    public function search(Request $request, ValidatorInterface $validator): ApiJsonResponse
    {
        try {
            $this->logger->info('Recherche d\'offres effectuée'.__CLASS__);
            $data = json_decode($request->getContent(), true);
            if (!is_array($data)) {
                return new ApiJsonResponse(null, Response::HTTP_BAD_REQUEST, 'Invalid JSON');
            }
            $model = LoanApplicationDTO::fromData($data);

            $errors = $validator->validate($model);

            if (count($errors) > 0) {
                $this->logger->error('Erreur de validation lors de la recherche d\'offres');

                return new ApiJsonResponse([
                    'errors' => $this->formatValidationErrors($errors),
                ], Response::HTTP_BAD_REQUEST, 'Validation error');
            }

            $offers = $this->loanOfferService->searchOffers($model);

            return new ApiJsonResponse([
                'amount' => $model->getAmount(),
                'duration' => $model->getDuration(),
                'name' => $model->getName(),
                'email' => $model->getEmail(),
                'phone' => $model->getPhone(),
                'offers' => $offers,
            ], Response::HTTP_OK, "Recherche d'offres effectuée");
        } catch (\Throwable $e) {
            $this->logger->error('Erreur lors de la recherche d\'offres: '.$e->getMessage());

            return new ApiJsonResponse(null, 500, 'Erreur interne serveur: '.$e->getMessage());
        }
    }

    /**
     * Formate les erreurs de validation en tableau clé => messages.
     */
    private function formatValidationErrors(iterable $errors): array
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $property = $error->getPropertyPath();
            $errorMessages[$property][] = $error->getMessage();
        }

        return $errorMessages;
    }
}
