<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class)
     */
    private $project_id;

    public function __construct($title, $avatar)
    {
        $this->title = $title;
        $this->avatar = $avatar;
        $this->project_id = new ArrayCollection();
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjectId(): Collection
    {
        return $this->project_id;
    }

    public function addProjectId(Project $projectId): self
    {
        if (!$this->project_id->contains($projectId)) {
            $this->project_id[] = $projectId;
        }

        return $this;
    }

    public function removeProjectId(Project $projectId): self
    {
        if ($this->project_id->contains($projectId)) {
            $this->project_id->removeElement($projectId);
        }

        return $this;
    }
}
