<?php

namespace App\Service;

use App\Model\Offre;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use RuntimeException;
use stdClass;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HttpClient
{
    private Client $client;
    private Serializer $serializer;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $_ENV['URL_BASE'],
        ]);
        $this->serializer = new Serializer(
            [
                new ObjectNormalizer(),
                new ArrayDenormalizer()
            ], 
            [new JsonEncoder()]);
    }

    /**
     * Récupéartion du token d'authentification
     * 
     * @return array 
     * @throws GuzzleException 
     * @throws Exception 
     * @throws InvalidArgumentException 
     * @throws RuntimeException 
     */
    private function getAccessToken()
    {
        $response = $this->client->post(
            $_ENV['AUTH_URL'],
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                RequestOptions::QUERY => [
                    'realm' => 'partenaire'
                ],
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $_ENV['CLIENT_ID'],
                    'client_secret' => $_ENV['TOKEN_API'],
                    'scope' => 'api_offresdemploiv2 o2dsoffre',
                ],
            ]
        );

        if ($response->getStatusCode() === 400) {
            throw new Exception($response->getReasonPhrase());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Récupération des offres
     * 
     * @return Offre[] 
     */
    public function getOffres(string $range = '', string $commune = ''): array
    {
        $queryParams = [];

        if (isset($range) && !empty($range)) {
            $queryParams['range'] = $range;
        }

        if (isset($commune) && !empty($range)) {
            $queryParams['commune'] = $commune;
        }

        $accessToken = $this->getAccessToken()['access_token'];

        $response = $this->client->get(
            'https://api.pole-emploi.io/partenaire/offresdemploi/v2/offres/search',
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                RequestOptions::QUERY => $queryParams,
            ]
        );
    
        return $this->parseResponse($response);
    }

    private function parseResponse(Response $response): array
    {
        $responseContent = $response->getBody()->getContents();
        $json = $this->serializer->decode($responseContent, 'json');

        if (is_null($json)) {
            throw new Exception('Erreur lors du parsing de la réponse HTTP');
        }

        return $this->serializer->deserialize(
            $this->serializer->serialize($json['resultats'], 'json'),
            'App\Model\Offre[]',
            'json'
        );
    }
}