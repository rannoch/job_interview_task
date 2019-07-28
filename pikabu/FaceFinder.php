<?php

require_once("FaceFinderInterface.php");
require_once("FaceRepositoryInterface.php");
require_once("FaceInterface.php");

class FaceFinder implements FaceFinderInterface
{
    private $repository;

    /**
     * FaceFinder constructor.
     * @param FaceRepositoryInterface $faceRepository
     */
    public function __construct(FaceRepositoryInterface $faceRepository)
    {
        $this->repository = $faceRepository;
    }

    /**
     * @param FaceInterface $face
     * @return FaceInterface[]
     */
    public function resolve(FaceInterface $face): array
    {
        if ($face->getId() == 0) {
            $this->repository->create($face);
        }

        $similarFaces = $this->repository->findFiveSimilar($face);

        return $similarFaces;
    }

    public function flush(): void
    {
        $this->repository->flush();
    }
}