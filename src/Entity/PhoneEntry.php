<?php

namespace App\Entity;

use App\Repository\PhoneEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhoneEntryRepository::class)]
class PhoneEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $priority;

    #[ORM\ManyToMany(targetEntity: PhoneGroups::class, mappedBy: 'contains_entries')]
    private $entryGroups;

    public function __construct()
    {
        $this->entryGroups = new ArrayCollection();
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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

//    /**
//     * @return mixed
//     */
//    public function getCurrentGroup()
//    {
//        return $this->currentGroup;
//    }
//
//    /**
//     * @param mixed $currentGroup
//     */
//    public function setCurrentGroup($currentGroup): void
//    {
//        $this->currentGroup = $currentGroup;
//    }


    /**
     * @return Collection<int, PhoneGroups>
     */
    public function getEntryGroups(): Collection
    {
        return $this->entryGroups;
    }


    public function addEntryGroup(PhoneGroups $entryGroup): self
    {
        if (!$this->entryGroups->contains($entryGroup)) {
            $this->entryGroups[] = $entryGroup;
            $entryGroup->addContainsEntry($this);
        }

        return $this;
    }

    public function removeEntryGroup(PhoneGroups $entryGroup): self
    {
        if ($this->entryGroups->removeElement($entryGroup)) {
            $entryGroup->removeContainsEntry($this);
        }

        return $this;
    }


}
