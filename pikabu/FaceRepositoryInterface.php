<?php

interface FaceRepositoryInterface
{
    public function all(): array;

    public function find($id): FaceInterface;

    public function create(FaceInterface $face): void;

    public function update(FaceInterface $face): void;

    public function delete(FaceInterface $face): void;

    public function flush();

    public function findFiveSimilar(FaceInterface $face);
}