<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Post;
use App\Entity\Tag;
use Grpc\Post\Post as GrpcPost;

class PostMapper
{
    public static function toGrpcPost(Post $post): GrpcPost
    {
        $grpcPost = new GrpcPost();
        $grpcPost->setId($post->getId());
        $grpcPost->setTitle($post->getTitle());
        $grpcPost->setText($post->getText());
        $grpcPost->setCreatedAt($post->getCreatedAt()?->format('d-m-Y'));
        $grpcPost->setUpdatedAt($post->getUpdatedAt()?->format('d-m-Y'));
        $grpcPost->setTags(array_map(
            static fn (Tag $tag) => $tag->getName(),
            $post->getTags()?->toArray()
        ));

        return $grpcPost;
    }
}
