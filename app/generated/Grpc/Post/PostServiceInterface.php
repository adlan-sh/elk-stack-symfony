<?php
# Generated by the protocol buffer compiler (roadrunner-server/grpc). DO NOT EDIT!
# source: app/proto/post.proto

namespace Grpc\Post;

use Spiral\RoadRunner\GRPC;

interface PostServiceInterface extends GRPC\ServiceInterface
{
    // GRPC specific service name.
    public const NAME = "grpc.post.PostService";

    /**
    * @param GRPC\ContextInterface $ctx
    * @param GetPostRequest $in
    * @return Post
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function getPost(GRPC\ContextInterface $ctx, GetPostRequest $in): Post;

    /**
    * @param GRPC\ContextInterface $ctx
    * @param CreatePostRequest $in
    * @return Post
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function createPost(GRPC\ContextInterface $ctx, CreatePostRequest $in): Post;

    /**
    * @param GRPC\ContextInterface $ctx
    * @param UpdatePostRequest $in
    * @return Post
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function updatePost(GRPC\ContextInterface $ctx, UpdatePostRequest $in): Post;

    /**
    * @param GRPC\ContextInterface $ctx
    * @param DeletePostRequest $in
    * @return Message
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function deletePost(GRPC\ContextInterface $ctx, DeletePostRequest $in): Message;
}
