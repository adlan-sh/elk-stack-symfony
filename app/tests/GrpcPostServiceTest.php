<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\GrpcPostService;
use Grpc\Post\CreatePostRequest;
use Grpc\Post\DeletePostRequest;
use Grpc\Post\GetPostRequest;
use Grpc\Post\Message;
use Grpc\Post\Post;
use Grpc\Post\UpdatePostRequest;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GrpcPostServiceTest extends KernelTestCase
{
    private static int $id;

    private GrpcPostService $grpcPostService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->grpcPostService = self::getContainer()->get(GrpcPostService::class);
    }

    public function test_createPost(): void
    {
        $ctx = $this->createMock(ContextInterface::class);
        $request = (new CreatePostRequest())
            ->setTitle('Post title')
            ->setText('Post text')
            ->setTags(['tag1', 'tag2']);

        $result = $this->grpcPostService->createPost($ctx, $request);

        if (!isset(self::$id)) {
            self::$id = $result->getId();
        }

        $post = (new Post())
            ->setId(self::$id)
            ->setCreatedAt($result->getCreatedAt())
            ->setTitle('Post title')
            ->setText('Post text')
            ->setTags(['tag1', 'tag2']);

        $this->assertEquals($post, $result);
    }

    public function test_getPost(): void
    {
        $ctx = $this->createMock(ContextInterface::class);
        $request = (new GetPostRequest())
            ->setId(self::$id);

        $result = $this->grpcPostService->getPost($ctx, $request);

        $post = (new Post())
            ->setId(self::$id)
            ->setCreatedAt($result->getCreatedAt())
            ->setTitle('Post title')
            ->setText('Post text')
            ->setTags(['tag1', 'tag2']);

        $this->assertEquals($post, $result);
    }

    public function test_updatePost(): void
    {
        $ctx = $this->createMock(ContextInterface::class);
        $request = (new UpdatePostRequest())
            ->setId(self::$id)
            ->setTitle('Post title changed')
            ->setText('Post text changed');

        $result = $this->grpcPostService->updatePost($ctx, $request);

        $post = (new Post())
            ->setId(self::$id)
            ->setCreatedAt($result->getCreatedAt())
            ->setUpdatedAt($result->getUpdatedAt())
            ->setTitle('Post title changed')
            ->setText('Post text changed')
            ->setTags(['tag1', 'tag2']);

        $this->assertEquals($post, $result);
    }

    public function test_deletePost(): void
    {
        $ctx = $this->createMock(ContextInterface::class);
        $request = (new DeletePostRequest())
            ->setId(self::$id);

        $result = $this->grpcPostService->deletePost($ctx, $request);

        $message = (new Message())->setMessage('Post was deleted');

        $this->assertEquals($message, $result);
    }

    protected function tearDown(): void
    {
        restore_exception_handler();
    }
}