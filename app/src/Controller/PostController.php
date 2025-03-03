<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    public function __construct(private readonly PostService $postService)
    {
    }

    #[Route('/post', methods: ['GET'])]
    public function getAllPosts(): Response
    {
        return $this->json($this->postService->getAllPosts());
    }

    #[Route('/post/{id}', methods: ['GET'])]
    public function getPost(int $id): Response
    {
        return $this->json($this->postService->getPost($id));
    }

    #[Route('/post', methods: ['POST'])]
    public function createPost(Request $request): Response
    {
        return $this->json($this->postService->createPost($request->toArray()['data']));
    }

    #[Route('/post/{id}', methods: ['PUT'])]
    public function updatePost(Request $request, int $id): Response
    {
        return $this->json($this->postService->updatePost($id, $request->toArray()['data']));
    }

    #[Route('/post/{id}', methods: ['DELETE'])]
    public function deletePost(int $id): Response
    {
        return $this->json($this->postService->deletePost($id));
    }
}
