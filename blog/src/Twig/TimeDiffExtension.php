<?php

namespace App\Twig;


use DateTime;
use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeDiffExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('time_diff', [$this, 'timeDiff']),
        ];
    }

    public function timeDiff(DateTimeImmutable $date): string
    {
        $now = new DateTime();
        $interval = $now->diff($date);
        if($interval->d>0){
            return $interval->d. ' jour'.($interval->d > 1 ? 's' : '');
        }        
        if($interval->h>0){
            return $interval->h. ' heure'.($interval->h > 1 ? 's' : '');
        }
        if($interval->i>0){
            return $interval->i. ' minute'.($interval->i > 1 ? 's' : '');
        }
        return "à l'instant";
    }
}
