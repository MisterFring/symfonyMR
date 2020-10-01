<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DatabaseService extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DatabaseService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getData(){
        return $this->entityManager->getRepository(Team::class);
    }

    public function getAll(){
        return $this->getData()->findAll();
    }

    public function getOneById(int $id){
        return $this->getData()->findOneById($id);
    }

    public function persistFlushProject(Project $project){
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }
}