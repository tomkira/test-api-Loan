<?php

namespace App\Tests\Service;

use App\DTO\LoanApplicationDTO;
use App\Service\LoanOfferService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class LoanOfferServiceTest extends TestCase
{
    public function testSearchOffersReturnsSortedOffers()
    {
        // Mock du Kernel pour le chemin du projet
        $kernel = $this->createMock(KernelInterface::class);
        $kernel->method('getProjectDir')->willReturn(__DIR__.'/../../');

        // Mock du modèle de demande
        $model = $this->createMock(LoanApplicationDTO::class);
        $model->method('getAmount')->willReturn(100000);
        $model->method('getDuration')->willReturn(15);
        $model->method('getName')->willReturn('Test');
        $model->method('getEmail')->willReturn('test@example.com');
        $model->method('getPhone')->willReturn('0600000000');

        // Préparer un faux fichier JSON dans le dossier storage
        $storageDir = __DIR__.'/../../storage/';
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
        $bnpFile = $storageDir.'BNP.json';

        $service = new LoanOfferService($kernel);
        $offers = $service->searchOffers($model);
        $this->assertIsArray($offers);
        $this->assertCount(3, $offers);
        $this->assertEquals('CARREFOURBANK', $offers[0]['partner']);
        $this->assertEquals(3.5, $offers[0]['rate']);
    }
}
