<?php

require_once(__DIR__ . "/FaceRepositoryMysql.php");

$faceRepository = new FaceRepositoryMysql();

$faceRepository->flush();
# add 1000 random faces
for ($a = 0; $a < 1000; $a++) {
    $faceRepository->create(
        new Face(
            rand(0, 100),
            rand(0, 1000),
            rand(0, 1000)
        )
    );
}
