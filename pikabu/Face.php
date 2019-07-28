<?php
require_once("FaceInterface.php");

class Face implements FaceInterface
{
    private $id;
    private $race;
    private $emotion;
    private $oldness;

    /**
     * Face constructor.
     * @param $race
     * @param $emotion
     * @param $oldness
     * @param int $id
     */
    public function __construct($race = null, $emotion = null, $oldness= null, $id = 0)
    {
        $this->id = $id;
        $this->race = $race;
        $this->emotion = $emotion;
        $this->oldness = $oldness;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRace(): int
    {
        return $this->race;
    }

    /**
     * @param mixed $race
     */
    public function setRace($race): void
    {
        $this->race = $race;
    }

    /**
     * @return mixed
     */
    public function getEmotion(): int
    {
        return $this->emotion;
    }

    /**
     * @param mixed $emotion
     */
    public function setEmotion($emotion): void
    {
        $this->emotion = $emotion;
    }

    /**
     * @return mixed
     */
    public function getOldness(): int
    {
        return $this->oldness;
    }

    /**
     * @param mixed $oldness
     */
    public function setOldness($oldness): void
    {
        $this->oldness = $oldness;
    }
}