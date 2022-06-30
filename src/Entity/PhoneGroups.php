<?php

namespace App\Entity;

use App\Repository\PhoneGroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhoneGroupsRepository::class)]
class PhoneGroups
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $priority;

    #[ORM\Column(type: 'boolean')]
    private $isDefault;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'phoneGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private $owned_by;

    #[ORM\ManyToMany(targetEntity: PhoneEntry::class, inversedBy: 'entryGroups')]
    private $contains_entries;

    public function __construct()
    {
        $this->contains_entries = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function isIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getOwnedBy(): ?user
    {
        return $this->owned_by;
    }

    public function setOwnedBy(?user $owned_by): self
    {
        $this->owned_by = $owned_by;

        return $this;
    }

    /**
     * @return Collection<int, PhoneEntry>
     */
    public function getContainsEntries(): Collection
    {
        return $this->contains_entries;
    }

    public function addContainsEntry(PhoneEntry $containsEntry): self
    {
        if (!$this->contains_entries->contains($containsEntry)) {
            $this->contains_entries[] = $containsEntry;
        }

        return $this;
    }

    public function removeContainsEntry(PhoneEntry $containsEntry): self
    {
        $this->contains_entries->removeElement($containsEntry);

        return $this;
    }

}
