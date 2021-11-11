<?php

namespace Alura\BuscadorDeCursos;

use Error;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    private ClientInterface $httpClient;
    private Crawler $crawler;

    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function buscar(string $url): array
    {
        try {
            $response = $this->httpClient->request('GET', $url);

            $html = $response->getBody();
            $this->crawler->addHtmlContent($html);

            $elementosCursos = $this->crawler->filter('p.formacao-passo-nome');
            $cursos = [];

            foreach ($elementosCursos as $elemento) {
                $cursos[] = $elemento->textContent;
            }

            return $cursos;
        } catch(ClientException $e) {
        echo "Erro inesperado" . PHP_EOL;
        }
    }
}
