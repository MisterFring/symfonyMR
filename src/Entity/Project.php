<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;


    /**
     * @ORM\Column(type="integer")
     */
    private $id_gitlab;

    /**
     * Project constructor.
     * @param $title
     * @param $id_gitlab
     */
    public function __construct($title, $id_gitlab)
    {
        $this->title = $title;
        $this->id_gitlab = $id_gitlab;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getIdGitlab(): ?int
    {
        return $this->id_gitlab;
    }

    public function setIdGitlab(int $id_gitlab): self
    {
        $this->id_gitlab = $id_gitlab;

        return $this;
    }
}
