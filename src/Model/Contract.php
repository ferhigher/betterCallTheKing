<?php

namespace App\Model;

class Contract
{

    private const K = 5;
    private const N = 2;
    private const V = 1;

    private $name;

    private $signature = [];

    private $totalValue;

    private $hasKing;

    private $hasHash;

    /**
     * @param $name
     * @param $signature
     */
    public function __construct($name, $signature)
    {
        $this->name = $name;
        $this->signature = str_split($signature);
        $this->hasHash = $this->hasHash($this->signature);
        $this->totalValue = $this->getTotal($this->signature);
    }


    public function hasHash(array $signature): bool
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
                $this->hasKing = true;
                $accum += self::K;
            }
            if ($item == 'N') {
                $accum += self::N;
            }
            if ($item == 'V' && !$this->hasKing) {
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
        return $this->hasKing;
    }

    public function setHasKing(?bool $hasKing): self
    {
        $this->hasKing = $hasKing;

        return $this;
    }

    public function getHasHash(): ?bool
    {
        return $this->hasHash;
    }

    public function setHasHash(?bool $hasHash): self
    {
        $this->hasHash = $hasHash;

        return $this;
    }

    public function getTotalValue(): ?int
    {
        return $this->totalValue;
    }

    public function setTotalValue(?int $totalValue): self
    {
        $this->totalValue = $totalValue;

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
