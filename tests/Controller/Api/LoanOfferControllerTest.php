<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoanOfferControllerTest extends WebTestCase
{
    private function clearStorage()
    {
        $storageDir = __DIR__.'/../../../../storage/';
        foreach (['BNP.json', 'CARREFOURBANK.json', 'SG.json'] as $file) {
            $f = $storageDir.$file;
            if (file_exists($f)) {
                unlink($f);
            }
        }
    }

    public function testSearchEndpointWithValidData()
    {
        $this->clearStorage();
        // Préparer un faux fichier JSON dans le dossier storage
        $storageDir = __DIR__.'/../../../../storage/';
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
        $bnpFile = $storageDir.'BNP.json';
        file_put_contents($bnpFile, '[{"montant":50000,"duree":15,"taux":2.5}]');

        $client = static::createClient();
        $client->request('POST', '/api/loan-offers/search', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'amount' => 50000,
            'duration' => 15,
            'name' => 'Test',
            'email' => 'test@example.com',
            'phone' => '0600000000',
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('offers', $responseData['data']);
        $this->assertIsArray($responseData['data']['offers']);
        foreach ($responseData['data']['offers'] as $offer) {
            $this->assertArrayHasKey('amount', $offer);
            $this->assertArrayHasKey('duration', $offer);
            $this->assertArrayHasKey('rate', $offer);
            $this->assertArrayHasKey('partner', $offer);
        }
        // Nettoyage
        unlink($bnpFile);
    }

    public function testSearchEndpointWithInvalidData()
    {
        $this->clearStorage();
        $client = static::createClient();
        $client->request('POST', '/api/loan-offers/search', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'amount' => null,
            'duration' => null,
            'name' => '',
            'email' => '',
            'phone' => '',
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('errors', $responseData['data']);
    }

    public function testSearchEndpointWithInvalidJson()
    {
        $this->clearStorage();
        $client = static::createClient();
        $client->request('POST', '/api/loan-offers/search', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], '{invalid_json');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testSearchEndpointWithUnauthorizedValues()
    {
        $this->clearStorage();
        $client = static::createClient();
        $client->request('POST', '/api/loan-offers/search', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'amount' => 12345, // valeur non autorisée
            'duration' => 99,  // valeur non autorisée
            'name' => 'Test',
            'email' => 'test@example.com',
            'phone' => '0600000000',
        ]));
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testSearchEndpointWithInvalidEmail()
    {
        $this->clearStorage();
        $client = static::createClient();
        $client->request('POST', '/api/loan-offers/search', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'amount' => 50000,
            'duration' => 15,
            'name' => 'Test',
            'email' => 'not-an-email',
            'phone' => '0600000000',
        ]));
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('errors', $responseData['data']);
        $this->assertArrayHasKey('email', $responseData['data']['errors']);
    }

    public function testSearchEndpointWithGetMethod()
    {
        $this->clearStorage();
        $client = static::createClient();
        $client->request('GET', '/api/loan-offers/search');
        $this->assertTrue(
            in_array($client->getResponse()->getStatusCode(), [404, 405]),
            'Should return 404 or 405 for GET method.'
        );
    }

    public function testGetLoanAmountsEndpoint()
    {
        $client = static::createClient();
        $client->request('GET', '/api/amounts');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('amounts', $responseData['data']);
        $this->assertEquals([50000, 100000, 200000, 500000], $responseData['data']['amounts']);
    }

    public function testGetLoanDurationsEndpoint()
    {
        $client = static::createClient();
        $client->request('GET', '/api/durations');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('durations', $responseData['data']);
        $this->assertEquals([15, 20, 25], $responseData['data']['durations']);
    }
}
