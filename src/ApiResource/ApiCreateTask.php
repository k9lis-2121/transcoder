<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\ApiResource;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use ApiPlatform\OpenApi\OpenApi;
use ArrayObject;
 
class ApiCreateTask implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $paths = $openApi->getPaths();


        $requestBody = new RequestBody(
            'Тело запроса для постановки в очередь на транскодирование',
            new ArrayObject([
                'application/json' => [

                    'schema' => [
                        'type' => 'object',
                        'required' => ['kinopoiskId', 'title', 'isSerial', 'isTrailler', 'uploadToSmarty', 'transcode', 'fileName', 'handhdd', 'selectedDisk', 'user'],
                        'properties' => [
                            'kinopoiskId' => [
                                'type' => 'string',
                                'example' => '401152',
                            ],
                            'title' => [
                                'type' => 'string',
                                'example' => 'Avatar_Aang_serial',
                            ],
                            'isSerial' => [
                                'type' => 'boolean',
                                'example' => false,
                            ],
                            'isTrailler' => [
                                'type' => 'boolean',
                                'example' => false,
                            ],
                            'seasonCount' => [
                                'type' => 'string',
                                'example' => '',
                            ],
                            'sameEpisodesCount' => [
                                'type' => 'boolean',
                                'example' => false,
                            ],
                            'sameEpisodes' => [
                                'type' => 'array',
                                'example' => [],
                            ],
                            'episodesCount' => [
                                'type' => 'array',
                                'example' => [],
                            ],
                            'uploadToSmarty' => [
                                'type' => 'string',
                                'example' => 'no',
                            ],
                            'transcode' => [
                                'type' => 'boolean',
                                'example' => true,
                            ],
                            'fileName' => [
                                'type' => 'string',
                                'example' => 'Avatar_1080p_by_krosh',
                            ],
                            'handhdd' => [
                                'type' => 'boolean',
                                'example' => false,
                            ],
                            'selectedDisk' => [
                                'type' => 'integer',
                                'example' => 20,
                            ],
                            'user' => [
                                'type' => 'string',
                                'example' => 'k9lis',
                            ],
                        ],
                    ]

                ],
            ]),
            false
        );

        $errorResponseSchema = new ArrayObject([
            'application/json' => [
                'schema' => [
                    'type' => 'object',
                    'properties' => [
                        'error' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'propertyPath' => [
                                        'type' => 'string',
                                    ],
                                    'message' => [
                                        'type' => 'string',
                                    ],
                                    'code' => [
                                        'type' => 'string',
                                    ],
                                ],
                            ],
                        ],
                        'message' => [
                            'type' => 'string',
                            'nullable' => true,
                        ],
                    ],
                ],
            ],
        ]);


        $operation = new Operation(
            'CreateTask',
            ['CreateTask'],
            [
                '200' => new Response(
                    'successful',
                    new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'message' => [
                                        'type' => 'boolean',
                                        'example' => true,
                                    ],
                                    'messageTitle' => [
                                        'type' => 'string',
                                        'example' => 'Транскодирование',
                                    ],
                                    'messageBody' => [
                                        'type' => 'string',
                                        'example' => 'Задача поставлена в очередь ',
                                    ],
                                ],
                            ],
                        ],
                    ])
                ),
                '400' => new Response(
                    'Bad Request',
                    $errorResponseSchema
                ),
            ],
            'POST',
            'Постановка в очередь на транскодирование 
            (Сериалы на текущтий момент не поддерживаются, указанные поля нужны для передачи обратно по завершению транскодирования)',
            null,
            [],
            $requestBody
        );


        $pathItem = new PathItem(
            null,
            'Кастомный эндпоинт для расчета цены',
            null,
            null,
            null,
            $operation,
            null,
            null,
            null,
            null,
            null
        );

        $paths->addPath('/api/create/task', $pathItem);

        // Возвращаем модифицированный объект OpenApi
        return $openApi->withPaths($paths);
    }
}
