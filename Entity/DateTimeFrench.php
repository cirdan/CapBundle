<?php
namespace SF\CapBundle\Entity;

class DateTimeFrench extends \DateTime {
    public function format($format) {
        $english = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
        $german = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche','Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim');
        return str_replace($english, $german, parent::format($format));
    }
}
?>