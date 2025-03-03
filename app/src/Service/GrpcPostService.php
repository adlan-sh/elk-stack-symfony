<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PostNotFoundException;
use App\Mapper\PostMapper;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Grpc\Post\CreatePostRequest;
use Grpc\Post\DeletePostRequest;
use Grpc\Post\GetPostRequest;
use Grpc\Post\Message;
use Grpc\Post\Post;
use Grpc\Post\PostServiceInterface;
use Grpc\Post\UpdatePostRequest;
use Psr\Log\LoggerInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;

readonly class GrpcPostService implements PostServiceInterface
{
    public function __construct(
        private PostRepository $postRepository,
        private TagRepository $tagRepository,
        private LoggerInterface $logstashLogger,
    ) {
    }

    public function getPost(ContextInterface $ctx, GetPostRequest $in): Post
    {
        $id = $in->getId();
        $post = $this->postRepository->get($id);

        if ($post === null) {
            throw new PostNotFoundException();
        }

        $this->logstashLogger->info('Get post by id [' . $id . ']');

        return PostMapper::toGrpcPost($post);
    }

    public function createPost(ContextInterface $ctx, CreatePostRequest $in): Post
    {
        $data = [
            'title' => $in->getTitle(),
            'text' => $in->getText(),
        ];

        if ($in->getTags()->count() > 0) {
            foreach ($in->getTags()->getIterator() as $tag) {
                $data['tags'][] = $tag;
            }

            $data['tags'] = $this->checkTags($data['tags']);
        }

        $this->logstashLogger->info('Create post');

        return PostMapper::toGrpcPost($this->postRepository->create($data));
    }

    public function updatePost(ContextInterface $ctx, UpdatePostRequest $in): Post
    {
        $id = $in->getId();
        $data = [
            'title' => $in->getTitle(),
            'text' => $in->getText(),
        ];

        if ($in->getTags()->count() > 0) {
            foreach ($in->getTags()->getIterator() as $tag) {
                $data['tags'][] = $tag;
            }

            $data['tags'] = $this->checkTags($data['tags']);
        }

        $post = $this->postRepository->update($id, $data);

        if ($post === null) {
            throw new PostNotFoundException();
        }

        $this->logstashLogger->info('Update post by id [' . $id . ']');

        return PostMapper::toGrpcPost($post);
    }

    public function deletePost(ContextInterface $ctx, DeletePostRequest $in): Message
    {
        $id = $in->getId();
        $isDeleted = $this->postRepository->delete($id);

        if (!$isDeleted) {
            throw new PostNotFoundException();
        }

        $this->logstashLogger->info('Delete post by id [' . $id . ']');

        return (new Message())->setMessage('Post was deleted');
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
