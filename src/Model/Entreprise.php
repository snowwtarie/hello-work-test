<?php

namespace App\Model;

class Entreprise
{
    private string $nom;

    public function __construct() {
        $this->nom = '';
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): self {
        $this->nom = $nom;

        return $this;
    }
}