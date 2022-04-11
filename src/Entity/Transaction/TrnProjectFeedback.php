<?php

namespace App\Entity\Transaction;

use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnProjectFeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnProjectFeedbackRepository::class)
 * @ORM\Table("trnprojectfeedback")
 */
class TrnProjectFeedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $topic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $feedback;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="guid", nullable=true)
     */
    private $rowId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnProjectFeedback")
     */
    private $trnProjects;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isApproved;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tredRemark;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $replyBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $replyOn;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(?string $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getCreatedBy(): ?AppUser
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?AppUser $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getRowId(): ?string
    {
        return $this->rowId;
    }

    public function setRowId(?string $rowId): self
    {
        $this->rowId = $rowId;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(?\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getTrnProjects(): ?TrnProject
    {
        return $this->trnProjects;
    }

    public function setTrnProjects(?TrnProject $trnProjects): self
    {
        $this->trnProjects = $trnProjects;

        return $this;
    }

    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;
        return $this;
    }

    public function getTredRemark(): ?string
    {
        return $this->tredRemark;
    }

    public function setTredRemark(?string $tredRemark): self
    {
        $this->tredRemark = $tredRemark;

        return $this;
    }

    public function getReplyBy(): ?AppUser
    {
        return $this->replyBy;
    }

    public function setReplyBy(?AppUser $replyBy): self
    {
        $this->replyBy = $replyBy;

        return $this;
    }

    public function getReplyOn(): ?\DateTimeInterface
    {
        return $this->replyOn;
    }

    public function setReplyOn(?\DateTimeInterface $replyOn): self
    {
        $this->replyOn = $replyOn;

        return $this;
    }
}
