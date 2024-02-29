<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FfmpegHandler\CommandMakerService;

class TestsController extends AbstractController
{
    #[Route('/tests', name: 'app_tests')]
    public function index(CommandMakerService $commandMakerService): Response
    {

        $result = $commandMakerService->__invoke('default', '/test/dir', '/HDD/1/VOD/content/000|test', '360p', '640', '360', 4500, 2000, '250ac');

        dump($result);
        return $this->render('tests/index.html.twig', [
            'controller_name' => 'TestsController',
        ]);
    }
}
