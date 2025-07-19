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
            new TwigFilter('duration', [$this, 'durationFilter']),
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

    public function durationFilter($integer)
    {
        $hour = floor($integer / 60);

        if ($hour ===  0.0) {
            return $integer . " min";
        } else {
            return $hour . "h " . $integer . "min";
        }
    }
}