<?php

require_once(__DIR__ . "/../FaceFinder.php");
require_once(__DIR__ . "/../Face.php");
require_once(__DIR__ . "/FaceRepositoryMock.php");
require_once(__DIR__ . "/../FaceRepositoryMysql.php");

use PHPUnit\Framework\TestCase;

class FaceFinderTest extends TestCase
{

    public function test__construct()
    {

    }

    public function testResolve()
    {
        $mock = new FaceRepositoryMock();

        $ff = new FaceFinder($mock);
        $ff->flush();

        # add and search first face
        $faces = $ff->resolve(new Face(1, 200, 500));
        assert(count($faces) === 1 && $faces[0]->getId() === 1);

        # add +1 face
        $faces = $ff->resolve(new Face(55, 100, 999));
        assert(count($faces) === 2 && $faces[0]->getId() === 2);

        # only search, not adding (because id != 0)
        $faces = $ff->resolve(new Face(55, 100, 999, 2));
        assert(count($faces) === 2 && $faces[0]->getId() === 2);

        # add 1000 random faces
        for ($a = 0; $a < 1000; $a++) {
            $ff->resolve(
                new Face(
                    rand(0, 100),
                    rand(0, 1000),
                    rand(0, 1000)
                )
            );
        }

        # let's recreate instance
        unset($ff);
        $ff = new FaceFinder($mock);

        # find known similar face and check first 3 records to match
        # Record with id=99999 not exists, this id is necessary to prevent
        # adding new face into DB
        $faces = $ff->resolve(new Face(54, 101, 998, 99999));
        assert(
            count($faces) === 5
            && (
                $faces[0]->getId() === 2
                || $faces[1]->getId() === 2
                || $faces[2]->getId() === 2
            )
        );
        $ff->flush();
    }

    public function testFlush()
    {

    }
}
