<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContractRepository::class)
 */
class Contract
{

    private const K = 5;
    private const N = 2;
    private const V = 1;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\Column(type="array")
     */
    private $signature = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $has_king;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $has_hash;

    /**
     * @param $name
     * @param $signature
     * @param $has_king
     * @param $has_hash
     */
    public function __construct($name, $signature, $has_king, $has_hash)
    {
        $this->name = $name;
        $this->signature = str_split($signature);
        $this->has_king = $has_king;
        $this->has_hash = $this->has_hash($this->signature);
        $this->total_value = $this->getTotal($this->signature);
    }


    public function has_hash(array $signature): bool
    {
        foreach ($signature as $item) {
            if ($item == '#') {
                return true;
            }
        }
        return false;

    }
    public function getTotal(array $word): int
    {
        $accum = 0;
        foreach ($word as $item) {
            if ($item == 'K') {
                $this->has_king = true;
                $accum += self::K;
            }
            if ($item == 'N') {
                $accum += self::N;
            }
            if ($item == 'V' && !$this->has_king) {
                $accum += self::V;
            }
        }
        return $accum;
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


    public function getHasKing(): ?bool
    {
        return $this->has_king;
    }

    public function setHasKing(?bool $has_king): self
    {
        $this->has_king = $has_king;

        return $this;
    }

    public function getHasHash(): ?bool
    {
        return $this->has_hash;
    }

    public function setHasHash(?bool $has_hash): self
    {
        $this->has_hash = $has_hash;

        return $this;
    }

    public function getTotalValue(): ?int
    {
        return $this->total_value;
    }

    public function setTotalValue(?int $total_value): self
    {
        $this->total_value = $total_value;

        return $this;
    }

    public function getSignature(): ?array
    {
        return $this->signature;
    }

    public function setSignature(array $signature): self
    {
        $this->signature = $signature;

        return $this;
    }



}
