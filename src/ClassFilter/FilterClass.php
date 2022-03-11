<?php

use App\Entity\Outing;
use Doctrine\Persistence\ManagerRegistry;

class FilterClass
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

}