<?php
$anneeDepart = substr($dateDepart, -19, 4);
$moisDepart = substr($dateDepart, -14, 2);
$jourDepart = substr($dateDepart, -11, 2);
$heureDepart = substr($dateDepart, -8, 2);
$minuteDepart = substr($dateDepart, -5, 2);
$secondeDepart = substr($dateDepart, -2, 2);
$anneeRetour = substr($dateRetour, -19, 4);
$moisRetour = substr($dateRetour, -14, 2);
$jourRetour = substr($dateRetour, -11, 2);
$heureRetour = substr($dateRetour, -8, 2);
$minuteRetour = substr($dateRetour, -5, 2);
$secondeRetour = substr($dateRetour, -2, 2);