<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('class_name', [$this, 'getClassName']),
            new TwigFilter('email_identifier', [$this, 'getEmailIdentifier']), // Ajouter ce filtre
        ];
    }

    public function getClassName($object): string
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    public function getEmailIdentifier($email): string
    {
        $parts = explode('@', $email);
        return $parts[0];
    }
}
