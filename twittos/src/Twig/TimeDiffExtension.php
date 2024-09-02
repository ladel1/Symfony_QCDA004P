<?php 

namespace App\Twig;

use DateTime;
use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeDiffExtension extends AbstractExtension{


    public function getFilters(): array
    {
        return [
            new TwigFilter('time_diff',[$this,'timeDiff'])
        ];
    }

    public function timeDiff(DateTimeImmutable $date):string{

        $now = new DateTime();
        $interval =  $now->diff($date);

        $prefix = "Il y a ";
        if($interval->y>0){
            return $prefix.$interval->y." années"; 
         }
        if($interval->m>0){
            return $prefix.$interval->m." mois"; 
         }
        if($interval->d>0){
           return $interval->d." jours"; 
        }
        if($interval->h>0){
            return $prefix.$interval->h." heures"; 
        }

        if($interval->i>0){
            return $prefix.$interval->i." minutes"; 
        }        

        if($interval->s>0){
            return $prefix.$interval->s." secondes"; 
        }    
        return "à l'instant";
    }

}