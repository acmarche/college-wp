<?php
/**
 * This file is part of wordpress application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/11/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AcMarche\College;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('niceDate', fn(array $event): string => $this->niceDate($event)),
        ];
    }

    public function niceDate(array $event): string
    {
        $format = 'Y-m-d H:i:s';
        $dateDebutTime = DateTime::createFromFormat($format,$event['date_debut']);
        $dateFinTime = DateTime::createFromFormat($format, $event['date_fin']);

        $dateDebut = $dateDebutTime->format('d-m-Y');
        $heureDebut = $dateDebutTime->format('H:i');

        $dateFin = $dateFinTime->format('d-m-Y');
        $heureFin = $dateFinTime->format('H:i');

        if ($dateDebut === $dateFin) {
            $txt = 'Le '.$dateDebut.' de '.$heureDebut.' à '.$heureFin;
        } else {
            $txt = 'Du '.$dateDebut.' à '.$heureDebut.
                ", jusqu'au ".$dateFin.' '.$heureFin;
        }

        return $txt;
    }

}
