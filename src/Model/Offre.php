<?php

namespace App\Model;

use PSpell\Config;

class Offre
{
    private string $id;

    private string $url;

    private string $intitule;

    private string $description;

    private string $dateCreation;

    private string $dateActualisation;

    private Contact $contact;

    private string $nomEntreprise;

    private Entreprise $entreprise;

    private string $typeContrat;

    public function __construct() {}

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id): self {
        $this->id = $id;

        return $this;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function setUrl(string $url): self {
        $this->url = $url;

        return $this;
    }

    public function getIntitule(): string {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self {
        $this->intitule = $intitule;

        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): string {
        return $this->dateCreation;
    }

    public function setDateCreation(string $dateCreation): self {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateActualisation(): string {
        return $this->dateActualisation;
    }

    public function setDateActualisation(string $dateActualisation): self {
        $this->dateActualisation = $dateActualisation;

        return $this;
    }

    public function getContact(): Contact {
        return $this->contact;
    }

    public function setContact(mixed $contact): self {
        if (is_array($contact)) {
            $this->contact = new Contact($contact['urlPostulation'] ?? '');
        } else {
            $this->contact = $contact;
        }


        return $this;
    }

    public function getEntreprise(): Entreprise {
        return $this->entreprise;
    }

    public function setEntreprise(mixed $entreprise): self {
        if (is_array($entreprise)) {
            $this->entreprise = new Entreprise($entreprise['nom'] ?? '');
        } else {
            $this->entreprise = $entreprise;
        }

        return $this;
    }

    public function getNomEntreprise(): string {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): self {
            $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getTypeContrat(): string {
        return $this->typeContrat;
    }

    public function setTypeContrat(string $typeContrat): self {
        $this->typeContrat = $typeContrat;

        return $this;
    }
}