<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function get(int $id): ?Post
    {
        return $this->find($id);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function create(array $data): Post
    {
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setText($data['text']);
        $post->setCreatedAt(new DateTimeImmutable());
        if (isset($data['tags'])) {
            $post->setTags($data['tags']);
        }
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();

        return $post;
    }

    public function update(int $id, array $data): ?Post
    {
        $post = $this->find($id);

        if ($post !== null) {
            $post->setTitle($data['title']);
            $post->setText($data['text']);
            $post->setUpdatedAt(new DateTimeImmutable());
            if (isset($data['tags'])) {
                $post->setTags($data['tags']);
            }
            $this->getEntityManager()->persist($post);
            $this->getEntityManager()->flush();
        }

        return $post;
    }

    public function delete(int $id): bool
    {
        $post = $this->find($id);

        if ($post !== null) {
            $this->getEntityManager()->remove($post);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
    }
}
