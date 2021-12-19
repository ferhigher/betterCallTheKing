<?php

namespace App\Model;

class Contract
{

    private const K = 5;
    private const N = 2;
    private const V = 1;

    private $name;

    private $signatures = [];

    private $totalValue;

    private $hasKing;

    private $hasHash;

    /**
     * @param $name
     * @param $signatures
     */
    public function __construct($name, $signatures)
    {
        $this->name = $name;
        $this->signatures = str_split($signatures);
        $this->hasHash = $this->hasHash($this->signatures);
        $this->totalValue = $this->getTotal($this->signatures);
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

    public function getTotal(array $signatures): int
    {
        $accum = 0;
        $this->hasKing = false;
        foreach ($signatures as $item) {
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


    public function valueToSignatures(int $gap, bool $king): array
    {
        $conjecture = [];
        $conjecture ['K'] = 0;
        $conjecture ['N'] = 0;
        $conjecture ['V'] = 0;

        $constants = [];
        $constants ['N'] = self::N;
        $constants ['K'] = self::K;
        $constants ['V'] = self::V;
        arsort($constants);
        $rest = 1;
        foreach ($constants as $value) {
            if ($value <= $gap) {
                $letter = array_search($value, $constants);
                if ($letter == 'K') {
                    $king = true;
                }
                if ($letter == 'V' && $king) {
                    break;
                }
                $quotient = intdiv($gap, $value);
                $rest = $gap % $value;
                $gap = $rest;
                $conjecture [$letter] += $quotient;
            }
        }
        if ($rest == 1) {
            $conjecture['N'] += 1;
        }

        return $conjecture;
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

    public function getSignatures(): ?array
    {
        return $this->signatures;
    }

    public function setSignatures(array $signatures): self
    {
        $this->signatures = $signatures;

        return $this;
    }


}
