<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketsRepository")
 */
class Tickets
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="tickets")
     */
    private $assign_to;

    public function __construct()
    {
        $this->assign_to = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAssignTo(): Collection
    {
        return $this->assign_to;
    }

    public function addAssignTo(User $assignTo): self
    {
        if (!$this->assign_to->contains($assignTo)) {
            $this->assign_to[] = $assignTo;
        }

        return $this;
    }

    public function removeAssignTo(User $assignTo): self
    {
        if ($this->assign_to->contains($assignTo)) {
            $this->assign_to->removeElement($assignTo);
        }

        return $this;
    }
}
