<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('days', [$this, 'daysFilter']),
        ];
    }

    public function daysFilter($array)
    {
        $days = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");

        $return = [];

        foreach ($array as $item) {
            $return[] = $days[$item];
        }

        return implode(",", $return);
    }
}