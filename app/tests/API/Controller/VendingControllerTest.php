<?php

namespace API\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VengingControllerTest extends WebTestCase
{
    public function testPostInsertCoinTenCentsAction()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/coin/insert/0.1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/xml'
            ],
            ''
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/coin/return');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('<item key="0">0.1</item>', $client->getResponse()->getContent());
    }

    public function testPostInsertNotValidCoinAction()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/coin/insert/1.9',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/xml'
            ],
            ''
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains('Invalid coin value', $client->getResponse()->getContent());
    }

    public function testGetBuyItemSodaAction()
    {
        $client = static::createClient();


        $coins = [
            '1',
            '1',
            '0.05'
        ];

        $payload = [
            'price' => 1.50,
            'amount' => 10
        ];

        $client->request(
            'PUT',
            '/api/service/item/soda',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/xml'
            ],
            json_encode($payload)
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        foreach ($coins as $coin) {
            $client->request(
                'POST',
                '/api/coin/insert/' . $coin,
                [],
                [],
                [
                    'CONTENT_TYPE' => 'application/json',
                    'ACCEPT' => 'application/xml'
                ],
                ''
            );
        }

        $client->request(
            'GET',
            '/api/item/buy/SODA',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/xml'
            ],
            ''
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<response><item key="0">SODA</item><item key="1">0.05</item><item key="2">0.25</item><item key="3">0.25</item></response>', $client->getResponse()->getContent());
    }

}
