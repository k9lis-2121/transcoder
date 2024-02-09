<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

use App\Message\TranscodeProcessingMessage;

class ApiCreateTaskController extends AbstractController
{

    public function __construct(private MessageBusInterface $messageBus){

    }

    #[Route('/api/create/task', name: 'app_api_create_task', methods: ['POST'])]
    public function index(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        dump($request->getContent());
        dump($data);

        $disk = $data['selectedDisk'];

        $nameDirTarget = "/HDD/$disk/VOD/content/Movie/".$data['kinopoiskId'].'|'.$data['title'].'/';
        $nameDirFilm = $data['fileName'];

        $this->messageBus->dispatch(new TranscodeProcessingMessage($nameDirFilm, $nameDirTarget, $data));
        $response = [
            'message' => true,
            'messageTitle' => 'Транскодирование',
            'messageBody' => 'Задача поставлена в очередь ',
        ];

        return $this->json($response);
    }
}
