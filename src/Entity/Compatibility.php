<?php

namespace App\Entity;

use App\Repository\CompatibilityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompatibilityRepository::class)
 */
class Compatibility
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="compatibilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="compatibilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $slave;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?Product
    {
        return $this->owner;
    }

    public function setOwner(?Product $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSlave(): ?Product
    {
        return $this->slave;
    }

    public function setSlave(?Product $slave): self
    {
        $this->slave = $slave;

        return $this;
    }
}
