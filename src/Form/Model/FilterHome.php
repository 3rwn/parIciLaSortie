<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FilterHome

{
    /**
     *
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

//    /**
//     * @param $campus
//     * @param $outingNameLike
//     * @param $startingDate
//     * @param $endingDate
//     * @param $isOrganizer
//     * @param $isRegister
//     * @param $isNotRegister
//     * @param $pastOutings
//     */
//    public function __construct($campus, $outingNameLike, $startingDate, $endingDate, $isOrganizer, $isRegister, $isNotRegister, $pastOutings)
//    {
//        $this->campus = $campus;
//        $this->outingNameLike = $outingNameLike;
//        $this->startingDate = $startingDate;
//        $this->endingDate = $endingDate;
//        $this->isOrganizer = $isOrganizer;
//        $this->isRegister = $isRegister;
//        $this->isNotRegister = $isNotRegister;
//        $this->pastOutings = $pastOutings;
//    }

    /**
     * @return mixed
     */
    public function getCampus()
    {
        return $this->campus;
    }



    /**
     * @param mixed $campus
     */
    public function setCampus($campus): void
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
     * @return FilterHome
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
     * @return FilterHome
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
     * @return FilterHome
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
     * @return FilterHome
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
     * @return FilterHome
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
     * @return FilterHome
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
     * @return FilterHome
     */
    public function setPastOutings($pastOutings)
    {
        $this->pastOutings = $pastOutings;
        return $this;
    }


}