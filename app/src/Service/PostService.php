<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use App\Exception\PostNotFoundException;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Psr\Log\LoggerInterface;

readonly class PostService
{
    public function __construct(
        private PostRepository $postRepository,
        private TagRepository $tagRepository,
        private LoggerInterface $logstashLogger,
    ) {
    }

    public function getPost(int $id): Post
    {
        $this->logstashLogger->info('Get post by id [' . $id . ']');
        $post = $this->postRepository->get($id);

        if ($post === null) {
            throw new PostNotFoundException();
        }

        return $post;
    }

    public function getAllPosts(): array
    {
        $this->logstashLogger->info('Get all posts');
        return $this->postRepository->getAll();
    }

    public function createPost(array $data): Post
    {
        $this->logstashLogger->info('Create post');
        if (isset($data['tags'])) {
            $data['tags'] = $this->checkTags($data['tags']);
        }

        return $this->postRepository->create($data);
    }

    public function updatePost(int $id, array $data): Post
    {
        $this->logstashLogger->info('Update post by id [' . $id . ']');

        if (isset($data['tags'])) {
            $data['tags'] = $this->checkTags($data['tags']);
        }

        $post = $this->postRepository->update($id, $data);

        if ($post === null) {
            throw new PostNotFoundException();
        }

        return $post;
    }

    public function deletePost(int $id): bool
    {
        $this->logstashLogger->info('Delete post by id [' . $id . ']');
        $isDeleted = $this->postRepository->delete($id);

        if (!$isDeleted) {
            throw new PostNotFoundException();
        }

        return true;
    }

    private function checkTags(array $tags): array
    {
        $result = [];
        foreach ($tags as $tag) {
            $result[] = $this->tagRepository->getOrCreate($tag);
        }

        return $result;
    }
}
