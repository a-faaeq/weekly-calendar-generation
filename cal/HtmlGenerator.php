<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class HtmlGenerator
{
    public function generateString($sessionsNumber, $groupSeance, $options, $orientation){
    $str = '';
    $firstHour = $groupSeance['firstHour'];
    $indexSession = 0;
    // Dans le cas ou il y aurait plus d'une session sur le même créneau
    if ($sessionsNumber > 1) {
        $percent = 100 / $sessionsNumber; // Pour déterminer la taille (hauteur ou largeur selon l'orientation) d'une séance
        if ($orientation === 1) {
            $str .='<div class="groupSeanceV">';
        } else if ($orientation === 0) {
            $str .='<div class="groupSeance">';
        }

        foreach($groupSeance['sessions'] as $seance) {
            $indexSession += 1;
            $coefficient = $seance->getLength() / 15;
            $length = $seance->getLength() + (($coefficient - 1)*3);

            $top = $distance = $seance->getStartTime() - $firstHour;
            if (($seance->getStartTime() - $firstHour) > 0) {
                $distance = $seance->getStartTime() - $firstHour;
                $newCoefficient = $distance / 15;
                $top = $distance + ($newCoefficient*3);
            }

            if ($orientation === 1) {
                $str .= '<div class="'. $options['seanceClass'] .'" style="width: '. $length . 'px; height:
                               '. $percent .'%; position: relative; left: ' . $top . 'px;">';
            } else if ($orientation === 0) {
                $str .= '<div class="'. $options['seanceClass'] .'" style="width: '. $percent . '%; height:
                               '. $length .'px; position: relative; top: ' . $top . 'px;">';
            }

            $str .= '<div class="'. $options['seanceTitleClass'] .'" style="position: relative; background-color: '. $seance->getColor().';">'
                . '<span>' . $seance->getCategory() . '</span><br/>'
                . '<span>' . $seance->getStartTimeFormatHour() .'</span>'
                . '<span>' . $seance->getEndTimeFormatHour() .'</span>'
                . '</div>'

                . '<div class="seance-content">'
                . '<div>' . $seance->getSubjectAlias() . '</div>'
                . '<div>' . $seance->getTeacher() . '</div>'
                . '<div>' . $seance->getRoomAlias() . '</div>'
                . '</div>'
            ;
            $str .= '</div>';
        }
        $str .='</div>';
        // Cas ou il n' y a pas de sessions regroupées
    } else {
        foreach($groupSeance['sessions'] as $seance) {
            $coefficient = $seance->getLength() / 15;
            // Longueur d'une séance en comptant les bordures (nbre de cell 15 px + 1 px)
            $length = $seance->getLength() + (($coefficient - 1)*3);

            if ($orientation === 1) {
                $str .= '<div class="'.$options['seanceClass'].'" style="width: '. $length. 'px; height: 100%; position: relative;">';
            } else if ($orientation === 0) {
                $str .= '<div class="'.$options['seanceClass'].'" style="width: 100%; height: '.$length.'px; position: relative;">';
            }

            $str .= '<div class="'. $options['seanceTitleClass'] .'" style="position: relative; background-color: '. $seance->getColor() .';">'
                . '<span>' .$seance->getCategory() . '</span>'
                . '<span>' . $seance->getStartTimeFormatHour() .'</span>-'
                . '<span>' . $seance->getEndTimeFormatHour() .'</span>'
                . '</div>'
                . '<div class="seance-content">'
                . '<div>' . $seance->getSubjectAlias() . '</div>'
                . '<div>' . $seance->getTeacher() . '</div>'
                . '<div>' . $seance->getRoomAlias() . '</div>'
                . '</div>'
            ;
            $str .= '</div>';
        }
    }
    return $str;
}
}