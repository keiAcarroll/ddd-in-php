<?php
declare(strict_types = 1);

/**
 * シンプルなRead Model（？）
 */
class PostController
{
    public function listAction()
    {
        $client = new ElasticsearchClientBuilder::create()->build();

        $response = $client->search([
            'index' => 'blog-engine',
            'type' => 'posts',
            'body' => [
                'sort' => [
                    'created_at' => ['order' => 'desc']
                ]
            ]
        ]);

        return [
            'posts' => $response
        ];
    }
}