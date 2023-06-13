<?php

namespace App\Model;

class Contact
{
    private string $urlPostulation;

    public function __construct()
    {
        $this->urlPostulation = '';
    }

    public function getUrlPostulation(): string {
        return $this->urlPostulation;
    }

    public function setUrlPostulation(string $url): self {
        $this->urlPostulation = $url;

        return $this;
    }
}