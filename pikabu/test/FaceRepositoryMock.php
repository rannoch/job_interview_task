<?php

require_once(__DIR__ . "/../FaceRepositoryInterface.php");

class FaceRepositoryMock implements FaceRepositoryInterface
{
    private $map;
    private $autoincrement = 1;

    public function all(): array
    {
        return array_values($this->map);
    }

    public function find($id): FaceInterface
    {
        return $this->map[$id] ?? null;
    }

    public function create(FaceInterface $face): void
    {
        $refObject = new ReflectionObject($face);
        $refProperty = $refObject->getProperty('id');
        $refProperty->setAccessible(true);
        $refProperty->setValue($face, $this->autoincrement++);

        $this->map[$face->getId()] = $face;
    }

    public function update(FaceInterface $face): void
    {
        // TODO: Implement update() method.
    }

    public function delete(FaceInterface $face): void
    {
        // TODO: Implement delete() method.
    }

    public function flush()
    {
        $this->map = [];
        $this->autoincrement = 1;
    }

    public function findFiveSimilar(FaceInterface $face)
    {
        $similarFaces = [];

        /** @var FaceInterface[] $faces */
        $faces = $this->all();

        $facesLast10000 = array_slice($faces, max(count($faces) - 10000, 0), count($faces));

        $facesDistancesMap = [];
        $facesMap = [];

        foreach ($facesLast10000 as $f) {
            $l = sqrt(($face->getRace() - $f->getRace()) ** 2 + ($face->getEmotion() - $f->getEmotion()) ** 2 +
                ($face->getOldness() - $f->getOldness()) ** 2);

            $facesDistancesMap[$f->getId()] = $l;
            $facesMap[$f->getId()] = $f;
        }

        asort($facesDistancesMap);

        foreach ($facesDistancesMap as $faceId => $distance) {
            $similarFaces[] = $facesMap[$faceId];

            if (count($similarFaces) >= 5) {
                break;
            }
        }

        return $similarFaces;
    }
}