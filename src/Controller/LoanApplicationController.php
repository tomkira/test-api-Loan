<?php

namespace App\Controller;

use App\Response\ApiJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoanApplicationController extends AbstractController
{
    #[Route('/', name: 'loan_application', methods: ['GET'])]
    public function index(): ApiJsonResponse
    {
        return new ApiJsonResponse([], 200, 'welcome to loan application api');
    }
}
