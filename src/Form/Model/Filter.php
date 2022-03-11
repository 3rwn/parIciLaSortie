<?php


class Filter

{
    /**
     * @var
     *
     */
    private $campus;

    /**
     *
     */
    private $outingNameLike;

    /**
     *
     */
    private $startingDate;

    /**
     *
     */
    private $endingDate;

    /**
     *
     */
    private $isOrganizer;

    /**
     *
     */
    private $isRegister;

    /**
     *
     */
    private $isNotRegister;

    /**
     *
     */
    private $pastOutings;

    /**
     * @param $campus
     * @param $outingNameLike
     * @param $startingDate
     * @param $endingDate
     * @param $isOrganizer
     * @param $isRegister
     * @param $isNotRegister
     * @param $pastOutings
     */
    public function __construct($campus, $outingNameLike, $startingDate, $endingDate, $isOrganizer, $isRegister, $isNotRegister, $pastOutings)
    {
        $this->campus = $campus;
        $this->outingNameLike = $outingNameLike;
        $this->startingDate = $startingDate;
        $this->endingDate = $endingDate;
        $this->isOrganizer = $isOrganizer;
        $this->isRegister = $isRegister;
        $this->isNotRegister = $isNotRegister;
        $this->pastOutings = $pastOutings;
    }

    /**
     * @return mixed
     */
    public function getCampus(): ?\App\Entity\Campus
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     */
    public function setCampus(?\App\Entity\Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return mixed
     */
    public function getOutingNameLike(): ?string
    {
        return $this->outingNameLike;
    }

    /**
     * @param mixed $outingNameLike
     * @return Filter
     */
    public function setOutingNameLike($outingNameLike)
    {
        $this->outingNameLike = $outingNameLike;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    /**
     * @param mixed $startingDate
     * @return Filter
     */
    public function setStartingDate($startingDate)
    {
        $this->startingDate = $startingDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->endingDate;
    }

    /**
     * @param mixed $endingDate
     * @return Filter
     */
    public function setEndingDate($endingDate)
    {
        $this->endingDate = $endingDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsOrganizer(): ?bool
    {
        return $this->isOrganizer;
    }

    /**
     * @param mixed $isOrganizer
     * @return Filter
     */
    public function setIsOrganizer($isOrganizer)
    {
        $this->isOrganizer = $isOrganizer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsRegister(): ?bool
    {
        return $this->isRegister;
    }

    /**
     * @param mixed $isRegister
     * @return Filter
     */
    public function setIsRegister($isRegister)
    {
        $this->isRegister = $isRegister;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsNotRegister(): ?bool
    {
        return $this->isNotRegister;
    }

    /**
     * @param mixed $isNotRegister
     * @return Filter
     */
    public function setIsNotRegister($isNotRegister)
    {
        $this->isNotRegister = $isNotRegister;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPastOutings(): ?bool
    {
        return $this->pastOutings;
    }

    /**
     * @param mixed $pastOutings
     * @return Filter
     */
    public function setPastOutings($pastOutings)
    {
        $this->pastOutings = $pastOutings;
        return $this;
    }


}