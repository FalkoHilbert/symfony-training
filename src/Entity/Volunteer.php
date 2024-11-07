<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startAt')]
    private ?\DateTimeImmutable $entAt = null;

    #[ORM\ManyToOne(inversedBy: 'volunteers')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'volunteers')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'volunteers')]
    private ?User $forUser = null;

    /**
     * @var Collection<int, Organization>
     */
    #[ORM\ManyToMany(targetEntity: Organization::class, mappedBy: 'volunteers')]
    private Collection $organizations;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEntAt(): ?\DateTimeImmutable
    {
        return $this->entAt;
    }

    public function setEntAt(\DateTimeImmutable $entAt): static
    {
        $this->entAt = $entAt;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getForUser(): ?User
    {
        return $this->forUser;
    }

    public function setForUser(?User $forUser): static
    {
        $this->forUser = $forUser;

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
            $organization->addVolunteer($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        if ($this->organizations->removeElement($organization)) {
            $organization->removeVolunteer($this);
        }

        return $this;
    }

    #[Assert\Callback()]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        if ($this->getEvent() instanceof Event) {
            if ($this->getStartAt()?->getTimestamp() < $this->getEvent()->getStartDate()?->getTimestamp()
                || $this->getStartAt()?->getTimestamp()> $this->getEvent()->getEndDate()?->getTimestamp()
            ) {
                $context->buildViolation("The volunteering start date should be comprised in the event's dates")
                    ->atPath('startAt')
                    ->addViolation();
            }
            if (
                $this->getEntAt()?->getTimestamp() < $this->getEvent()->getStartDate()?->getTimestamp()
                || $this->getEntAt()?->getTimestamp() > $this->getEvent()->getEndDate()?->getTimestamp()
            ) {
                $context->buildViolation("The volunteering start date should be comprised in the event's dates")
                    ->atPath('endAt')
                    ->addViolation();
            }
        }

        if (null === $this->getEvent() && null === $this->getProject()) {
            $context->buildViolation("You have to select and event or a project, or both")
                ->atPath('event')
                ->addViolation();
            $context->buildViolation("You have to select and event or a project, or both")
                ->atPath('project')
                ->addViolation();
        }

        if ($this->getEvent() instanceof Event
            && $this->getProject() instanceof Project
            && !$this->getProject()->getEvents()->contains($this->getEvent())
        ) {

            $context->buildViolation("You have to select an event from the chosen project")
                ->atPath('event')
                ->addViolation();
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'startAt' => $this->getStartAt(),
            'entAt' => $this->getEntAt(),
            'eventId' => $this->getEvent()?->getId(),
            'event' => $this->getEvent()?->getName(),
            'projectId' => $this->getProject()?->getId(),
            'project' => $this->getProject()?->getName(),
            'forUserId' => $this->getForUser()?->getId(),
            'forUser' => $this->getForUser()?->getEmail(),
            'organizationIds' => $this->getOrganizations()->map(fn(Organization $organization) => $organization->getId())->toArray(),
        ];
    }
}
