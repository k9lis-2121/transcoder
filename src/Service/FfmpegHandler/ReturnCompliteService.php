<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\Service\FfmpegHandler;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ReturnCompliteService extends TranscoderBaseService
{
    
    private $client;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameterBag){
        parent::__construct($parameterBag);
        $this->client = $client;
    }


    public function setSmarty($otherData){

        $otherData['transcode'] = false;
        $otherData['uploadToSmarty'] = 'yes';

        $response = $this->client->request(
            'POST',
            $this->contentMaker.'/api/maker/dir',
            [
                'json' => $otherData,
                'verify_peer' => false,
                'verify_host' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        dump($statusCode);
        dump($content);
    }
}