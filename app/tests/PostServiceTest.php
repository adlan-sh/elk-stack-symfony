<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Post;
use App\Exception\PostNotFoundException;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostServiceTest extends KernelTestCase
{
    private static int $id;

    private PostService $postService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->postService = self::getContainer()->get(PostService::class);
    }

    public function test_createPost(): void
    {
        $data = [
            'title' => 'Post title',
            'text' => 'Post text',
            'tags' => ['tag1', 'tag2'],
        ];
        $postDb = $this->postService->createPost($data);

        if (!isset(self::$id)) {
            self::$id = $postDb->getId();
        }

        $post = (new Post())
            ->setId(self::$id)
            ->setTitle('Post title')
            ->setText('Post text')
            ->setCreatedAt($postDb->getCreatedAt())
            ->setTags(['tag1', 'tag2']);

        $this->assertObjectEquals($postDb, $post);
    }

    public function test_getPost(): void
    {
        $postDb = $this->postService->getPost(self::$id);

        $post = (new Post())
            ->setId(self::$id)
            ->setTitle('Post title')
            ->setText('Post text')
            ->setCreatedAt($postDb->getCreatedAt())
            ->setTags(['tag1', 'tag2']);

        $this->assertObjectEquals($post, $postDb);
    }

    public function test_updatePost(): void
    {
        $data = [
            'title' => 'Post title changed',
            'text' => 'Post text changed',
        ];
        $postDb = $this->postService->updatePost(self::$id, $data);

        $post = (new Post())
            ->setId(self::$id)
            ->setTitle('Post title changed')
            ->setText('Post text changed')
            ->setCreatedAt($postDb->getCreatedAt())
            ->setUpdatedAt($postDb->getUpdatedAt())
            ->setTags(['tag1', 'tag2']);

        $this->assertObjectEquals($post, $postDb);
    }

    public function test_deletePost(): void
    {
        $this->postService->deletePost(self::$id);

        $this->expectException(PostNotFoundException::class);

        $this->postService->getPost(self::$id);
    }

    protected function tearDown(): void
    {
        restore_exception_handler();
    }
}
