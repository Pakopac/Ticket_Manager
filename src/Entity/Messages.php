<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessagesRepository")
 */
class Messages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tickets", inversedBy="messages")
     */
    private $ticket_assign;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     */
    private $message_author;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTicketAssign(): ?Tickets
    {
        return $this->ticket_assign;
    }

    public function setTicketAssign(?Tickets $ticket_assign): self
    {
        $this->ticket_assign = $ticket_assign;

        return $this;
    }

    public function getMessageAuthor(): ?User
    {
        return $this->message_author;
    }

    public function setMessageAuthor(?User $message_author): self
    {
        $this->message_author = $message_author;

        return $this;
    }
}
