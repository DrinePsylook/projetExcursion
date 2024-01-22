<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="script.js" defer></script>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    include('head.php'); //menu de navigation unique en php//
    ?>
</head>

<body>
<header>
    <?php
    include('menuProjet.php'); //menu de navigation unique en php//
    ?>
</header>
<?php
//Connexion à la BD
include "requeteConnexionBD.php";
?>


<section id="accueil"  class="espaceMenu"> <!--  permet au menu de ne pas passer au-dessus de la première ancre-->
</section>
<section class="ancre4">
    <?php

    //Test de la valeur du menu déroulant
//------------------------------------------------------------------------------------------------------------------------
    //Départ Colmar
    if(isset($_POST['rechercheDepart']) && $_POST['rechercheDepart'] == "departColmar"){

        //Requête d'affichage
        $date = date("Y-m-d H:i:s");
        $resultat = $db->query('SELECT e.idExcursion, e.dateDepart, e.dateRetour, t.idType, t.nomExcursion, t.tarif, t.nbreMaxParticipants, t.idLieuDepart, l.nomLieu 
                                            FROM excursions e 
                                                INNER JOIN typesexcursion t ON e.idType = t.idType 
                                                INNER JOIN lieux l ON t.idLieuDepart = l.idLieu 
                                            WHERE dateDepart > "'.$date.'" AND t.idLieuDepart = 1') or die(print_r($db->errorInfo()));

        foreach ($resultat as $row) {

            //Requête : contage du nombre d'inscrits selon l'idExcursion
            $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits 
                                                        FROM inscriptions 
                                                        WHERE idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
            $testRetourBase = $countexcursion->rowCount();
            if($testRetourBase>0){
                foreach ($countexcursion as $row2){
                    $nbreParticipantsInscrits = $row2['totalinscrits'];
                }
            }else{
                $nbreParticipantsInscrits = 0;
            }

            $rechercheIdExcursion = $row['idExcursion'];
            $rechercheDateDepart = $row['dateDepart'];
            $rechercheDateRetour = $row['dateRetour'];
            $rechercheIdType = $row['idType'];
            $rechercheNomExcursion = $row['nomExcursion'];

            //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
            $nbreParticipantsRestants = '';
            $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;

            //S'il reste des inscriptions pour l'idExcursion
            if($nbreParticipantsRestants > 0){
                echo "<div class=\"carteRecherche\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formAffichRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$rechercheIdExcursion."' type='hidden' />";

                //Requete chemin photo
                $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description 
                                                        FROM photos p 
                                                            INNER JOIN illustrations i ON p.idPhoto = i.idPhoto 
                                                            INNER JOIN excursions e ON i.idType = e.idType  
                                                        WHERE e.idExcursion = '.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                $cheminPhoto = '';
                $descriptionPhoto = '';
                foreach ($affichagephoto as $row2){
                    $cheminPhoto = $row2['cheminPhoto'];
                    $descriptionPhoto = $row2['description'];
                }
                //Affichage du détail de la date et de l'heure
                $dateDepart = $row['dateDepart'];
                $dateRetour = $row['dateRetour'];
                include "affichageDateHeure.php";

                echo "<div class='hexagone2'>
                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
                    </div>
                    <div class='texteRecherche'>
                    <h3>".$rechercheNomExcursion."</h3>
                    <p class='detailConsultation'>
                    <span class=\"libelle\">Lieu de départ : </span>";

                $nomLieuDepart = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuDepart 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                foreach($nomLieuDepart as $row4){
                    echo $row4['nomLieu'];
                }

                echo "<br/><span class=\"libelle\">Lieu d'arrivée : </span>";

                $nomLieuArrivee = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                foreach($nomLieuArrivee as $row5){
                    echo $row5['nomLieu'];
                }

                echo "<br/>
                    <span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
                    </p>
                    </div>
                    <div class='alerteRecherche'>
                    <p class='alerte2'>";

                echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";

                echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
                </div>
            </div>";
            }
        }

//////////------------------------------------------------------------------------------------------------------------------------------------------------

        //Départ Mulhouse
    }else if(isset($_POST['rechercheDepart']) && $_POST['rechercheDepart'] == "departMulhouse") {
        //Requête d'affichage
        $date = date("Y-m-d H:i:s");
        $resultat = $db->query('SELECT e.idExcursion, e.dateDepart, e.dateRetour, t.idType, t.nomExcursion, t.tarif, t.nbreMaxParticipants, t.idLieuDepart, l.nomLieu 
                                            FROM excursions e 
                                                INNER JOIN typesexcursion t ON e.idType = t.idType 
                                                INNER JOIN lieux l ON t.idLieuDepart = l.idLieu 
                                            WHERE dateDepart > "' . $date . '" AND t.idLieuDepart = 2') or die(print_r($db->errorInfo()));

        foreach ($resultat as $row) {

            //Requête : contage du nombre d'inscrits selon l'idExcursion
            $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits 
                                                        FROM inscriptions 
                                                        WHERE idExcursion = ' . $row['idExcursion']) or die(print_r($db->errorInfo()));
            $testRetourBase = $countexcursion->rowCount();
            if ($testRetourBase > 0) {
                foreach ($countexcursion as $row2) {
                    $nbreParticipantsInscrits = $row2['totalinscrits'];
                }
            } else {
                $nbreParticipantsInscrits = 0;
            }

            $rechercheIdExcursion = $row['idExcursion'];
            $rechercheDateDepart = $row['dateDepart'];
            $rechercheDateRetour = $row['dateRetour'];
            $rechercheIdType = $row['idType'];
            $rechercheNomExcursion = $row['nomExcursion'];

            //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
            $nbreParticipantsRestants = '';
            $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;

            //S'il reste des inscriptions pour l'idExcursion
            if ($nbreParticipantsRestants > 0) {
                echo "<div class=\"carteRecherche\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formAffichRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='" . $rechercheIdExcursion . "' type='hidden' />";

                //Requete chemin photo
                $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description 
                                                        FROM photos p 
                                                            INNER JOIN illustrations i ON p.idPhoto = i.idPhoto 
                                                            INNER JOIN excursions e ON i.idType = e.idType  
                                                        WHERE e.idExcursion = ' . $rechercheIdExcursion) or die(print_r($db->errorInfo()));
                $cheminPhoto = '';
                $descriptionPhoto = '';
                foreach ($affichagephoto as $row2) {
                    $cheminPhoto = $row2['cheminPhoto'];
                    $descriptionPhoto = $row2['description'];
                }
                //Affichage du détail de la date et de l'heure
                $dateDepart = $row['dateDepart'];
                $dateRetour = $row['dateRetour'];
                include "affichageDateHeure.php";

                echo "<div class='hexagone2'>
                    <img class='imgCartes' src='" . $cheminPhoto . "' alt='" . $descriptionPhoto . "'>
                    </div>
                    <div class='texteRecherche'>
                    <h3>" . $rechercheNomExcursion . "</h3>
                    <p class='detailConsultation'>
                    <span class=\"libelle\">Lieu de départ : </span>";

                $nomLieuDepart = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuDepart 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion =' . $rechercheIdExcursion) or die(print_r($db->errorInfo()));
                foreach ($nomLieuDepart as $row4) {
                    echo $row4['nomLieu'];
                }

                echo "<br/><span class=\"libelle\">Lieu d'arrivée : </span>";

                $nomLieuArrivee = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion =' . $rechercheIdExcursion) or die(print_r($db->errorInfo()));
                foreach ($nomLieuArrivee as $row5) {
                    echo $row5['nomLieu'];
                }

                echo "<br/>
                    <span class=\"libelle\">Date de départ : </span>" . $jourDepart . "/" . $moisDepart . "/" . $anneeDepart . " à " . $heureDepart . "h" . $minuteDepart . " <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>" . $jourRetour . "/" . $moisRetour . "/" . $anneeRetour . " à " . $heureRetour . "h" . $minuteRetour . "
                    <br><span class=\"libelle\">Tarif : </span>" . $row['tarif'] . " €
                    </p>
                    </div>
                    <div class='alerteRecherche'>
                    <p class='alerte2'>";

                echo "Il ne reste plus que " . $nbreParticipantsRestants . " places<br/>(Participants max : " . $row['nbreMaxParticipants'] . ")";

                echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
                </div>
            </div>";
            }
        }


//------------------------------------------------------------------------------------------------------------------------------------------------------

//////////Recherche mois+année

    }else if(isset($_POST['rechercheMois']) && $_POST['rechercheMois']  > 0 && isset($_POST['rechercheAnnee']) && $_POST['rechercheAnnee'] > 0){

            //Requête d'affichage
            $date = date("Y-m-d H:i:s");
            $affichageRechercheMoisAnnee = $db->query('SELECT e.idExcursion, e.dateDepart, e.dateRetour, t.idType, t.nomExcursion, t.tarif, t.nbreMaxParticipants, t.idLieuDepart, l.nomLieu 
                                                                    FROM excursions e 
                                                                        INNER JOIN typesexcursion t ON e.idType = t.idType 
                                                                        INNER JOIN lieux l ON t.idLieuDepart = l.idLieu 
                                                                    WHERE dateDepart LIKE "%'.$_POST['rechercheAnnee'].'-'.$_POST['rechercheMois'].'-%"') or die(print_r($db->errorInfo()));

            //Test du retour de la base de données
            $testAffichageRechercheMoisAnnee = $affichageRechercheMoisAnnee->rowCount();
            if($testAffichageRechercheMoisAnnee>0){
                foreach ($affichageRechercheMoisAnnee as $row) {

                    //Requête : contage du nombre d'inscrits selon l'idExcursion
                    $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits 
                                                                FROM inscriptions 
                                                                WHERE idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
                    $testRetourBase = $countexcursion->rowCount();
                    if($testRetourBase>0){
                        foreach ($countexcursion as $row2){
                            $nbreParticipantsInscrits = $row2['totalinscrits'];
                        }
                    }else{
                        $nbreParticipantsInscrits = 0;
                    }

                    $rechercheIdExcursion = $row['idExcursion'];
                    $rechercheDateDepart = $row['dateDepart'];
                    $rechercheDateRetour = $row['dateRetour'];
                    $rechercheIdType = $row['idType'];
                    $rechercheNomExcursion = $row['nomExcursion'];

                    //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
                    $nbreParticipantsRestants = '';
                    $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;

                    //S'il reste des inscriptions pour l'idExcursion
                    if($nbreParticipantsRestants > 0){
                        echo "<div class=\"carteRecherche\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formAffichRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$rechercheIdExcursion."' type='hidden' />";

                        //Requete chemin photo
                        $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description 
                                                                    FROM photos p 
                                                                        INNER JOIN illustrations i ON p.idPhoto = i.idPhoto 
                                                                        INNER JOIN excursions e ON i.idType = e.idType  
                                                                    WHERE e.idExcursion = '.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                        $cheminPhoto = '';
                        $descriptionPhoto = '';
                        foreach ($affichagephoto as $row2){
                            $cheminPhoto = $row2['cheminPhoto'];
                            $descriptionPhoto = $row2['description'];
                        }
                        //Affichage du détail de la date et de l'heure
                        $dateDepart = $row['dateDepart'];
                        $dateRetour = $row['dateRetour'];
                        include "affichageDateHeure.php";

                        echo "<div class='hexagone2'>
                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
                    </div>
                    <div class='texteRecherche'>
                    <h3>".$rechercheNomExcursion."</h3>
                    <p class='detailConsultation'>
                    <span class=\"libelle\">Lieu de départ : </span>";

                        $nomLieuDepart = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuDepart 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                        foreach($nomLieuDepart as $row4){
                            echo $row4['nomLieu'];
                        }

                        echo "<br/><span class=\"libelle\">Lieu d'arrivée : </span>";

                        $nomLieuArrivee = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                        foreach($nomLieuArrivee as $row5){
                            echo $row5['nomLieu'];
                        }

                        echo "<br/>
                    <span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
                    </p>
                    </div>
                    <div class='alerteRecherche'>
                    <p class='alerte2'>";

                        echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";

                        echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
                </div>
            </div>";
                    }
                }
            }else{
                echo "<section class='verif'>Pas d'excursion disponible pour le mois et l'année choisis.</section>";
            }
        //////----------------------------------------------------------------------------------------------------------------------------------------------
///

        }else if(isset($_POST['rechercheMois']) && $_POST['rechercheMois'] > 0){

        //Requête d'affichage par mois
        $date = date("Y-m-d H:i:s");
        $affichageRechercheMois = $db->query('SELECT e.idExcursion, e.dateDepart, e.dateRetour, t.idType, t.nomExcursion, t.tarif, t.nbreMaxParticipants, t.idLieuDepart, l.nomLieu 
                                                            FROM excursions e 
                                                                INNER JOIN typesexcursion t ON e.idType = t.idType 
                                                                INNER JOIN lieux l ON t.idLieuDepart = l.idLieu 
                                                            WHERE dateDepart LIKE "%-'.$_POST['rechercheMois'].'-%"') or die(print_r($db->errorInfo()));

        //Test du retour de la base de données
        $testAffichageRechercheMois = $affichageRechercheMois->rowCount();
        if($testAffichageRechercheMois>0){
            foreach ($affichageRechercheMois as $row) {

                //Requête : contage du nombre d'inscrits selon l'idExcursion
                $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits 
                                                            FROM inscriptions 
                                                            WHERE idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
                $testRetourBase = $countexcursion->rowCount();
                if($testRetourBase>0){
                    foreach ($countexcursion as $row2){
                        $nbreParticipantsInscrits = $row2['totalinscrits'];
                    }
                }else{
                    $nbreParticipantsInscrits = 0;
                }

                $rechercheIdExcursion = $row['idExcursion'];
                $rechercheDateDepart = $row['dateDepart'];
                $rechercheDateRetour = $row['dateRetour'];
                $rechercheIdType = $row['idType'];
                $rechercheNomExcursion = $row['nomExcursion'];

                //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
                $nbreParticipantsRestants = '';
                $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;

                //S'il reste des inscriptions pour l'idExcursion
                if($nbreParticipantsRestants > 0){
                    echo "<div class=\"carteRecherche\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formAffichRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$rechercheIdExcursion."' type='hidden' />";

                    //Requete chemin photo
                    $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description 
                                                                FROM photos p 
                                                                    INNER JOIN illustrations i ON p.idPhoto = i.idPhoto 
                                                                    INNER JOIN excursions e ON i.idType = e.idType  
                                                                WHERE e.idExcursion = '.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    $cheminPhoto = '';
                    $descriptionPhoto = '';
                    foreach ($affichagephoto as $row2){
                        $cheminPhoto = $row2['cheminPhoto'];
                        $descriptionPhoto = $row2['description'];
                    }
                    //Affichage du détail de la date et de l'heure
                    $dateDepart = $row['dateDepart'];
                    $dateRetour = $row['dateRetour'];
                    include "affichageDateHeure.php";

                    echo "<div class='hexagone2'>
                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
                    </div>
                    <div class='texteRecherche'>
                    <h3>".$rechercheNomExcursion."</h3>
                    <p class='detailConsultation'>
                    <span class=\"libelle\">Lieu de départ : </span>";

                    $nomLieuDepart = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuDepart 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    foreach($nomLieuDepart as $row4){
                        echo $row4['nomLieu'];
                    }

                    echo "<br/><span class=\"libelle\">Lieu d'arrivée : </span>";

                    $nomLieuArrivee = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    foreach($nomLieuArrivee as $row5){
                        echo $row5['nomLieu'];
                    }

                    echo "<br/>
                    <span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
                    </p>
                    </div>
                    <div class='alerteRecherche'>
                    <p class='alerte2'>";

                    echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";

                    echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
                </div>
            </div>";
                }
            }
        }else{
            echo "<section class='verif'>Pas d'excursion disponible pour le mois choisi.</section>";
        }
//////-----------------------------------------------------------------------------------------------------------------------------------

    }else if(isset($_POST['rechercheAnnee']) && $_POST['rechercheAnnee'] > 0){

        //Requête d'affichage par année
        $date = date("Y-m-d H:i:s");
        $affichageRechercheAnnee = $db->query('SELECT e.idExcursion, e.dateDepart, e.dateRetour, t.idType, t.nomExcursion, t.tarif, t.nbreMaxParticipants, t.idLieuDepart, l.nomLieu 
                                                            FROM excursions e 
                                                                INNER JOIN typesexcursion t ON e.idType = t.idType 
                                                                INNER JOIN lieux l ON t.idLieuDepart = l.idLieu 
                                                            WHERE dateDepart LIKE "%'.$_POST['rechercheAnnee'].'-%"') or die(print_r($db->errorInfo()));

        //Test du retour de la base de données

        $testAffichageRechercheAnnee = $affichageRechercheAnnee->rowCount();
        if($testAffichageRechercheAnnee>0){
            foreach ($affichageRechercheAnnee as $row) {

                //Requête : contage du nombre d'inscrits selon l'idExcursion
                $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits 
                                                            FROM inscriptions 
                                                            WHERE idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
                $testRetourBase = $countexcursion->rowCount();
                if($testRetourBase>0){
                    foreach ($countexcursion as $row2){
                        $nbreParticipantsInscrits = $row2['totalinscrits'];
                    }
                }else{
                    $nbreParticipantsInscrits = 0;
                }

                $rechercheIdExcursion = $row['idExcursion'];
                $rechercheDateDepart = $row['dateDepart'];
                $rechercheDateRetour = $row['dateRetour'];
                $rechercheIdType = $row['idType'];
                $rechercheNomExcursion = $row['nomExcursion'];

                //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
                $nbreParticipantsRestants = '';
                $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;

                //S'il reste des inscriptions pour l'idExcursion
                if($nbreParticipantsRestants > 0){
                    echo "<div class=\"carteRecherche\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formAffichRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$rechercheIdExcursion."' type='hidden' />";

                    //Requete chemin photo
                    $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description 
                                                                FROM photos p 
                                                                    INNER JOIN illustrations i ON p.idPhoto = i.idPhoto 
                                                                    INNER JOIN excursions e ON i.idType = e.idType  
                                                                WHERE e.idExcursion = '.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    $cheminPhoto = '';
                    $descriptionPhoto = '';
                    foreach ($affichagephoto as $row2){
                        $cheminPhoto = $row2['cheminPhoto'];
                        $descriptionPhoto = $row2['description'];
                    }
                    //Affichage du détail de la date et de l'heure
                    $dateDepart = $row['dateDepart'];
                    $dateRetour = $row['dateRetour'];
                    include "affichageDateHeure.php";

                    echo "<div class='hexagone2'>
                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
                    </div>
                    <div class='texteRecherche'>
                    <h3>".$rechercheNomExcursion."</h3>
                    <p class='detailConsultation'>
                    <span class=\"libelle\">Lieu de départ : </span>";

                    $nomLieuDepart = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuDepart 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    foreach($nomLieuDepart as $row4){
                        echo $row4['nomLieu'];
                    }

                    echo "<br/><span class=\"libelle\">Lieu d'arrivée : </span>";

                    $nomLieuArrivee = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    foreach($nomLieuArrivee as $row5){
                        echo $row5['nomLieu'];
                    }

                    echo "<br/>
                    <span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
                    </p>
                    </div>
                    <div class='alerteRecherche'>
                    <p class='alerte2'>";

                    echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";

                    echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
                </div>
            </div>";
                }
            }
        }else{
            echo "<section class='verif'>Pas d'excursion disponible pour l'année choisie.</section>";
        }
    //}else if(isset($_POST['motCle']) && !empty($_POST['motCle'])){
//
//            //Requête d'affichage
//            $date = date("Y-m-d H:i:s");
//            $affichageRechercheMotCle = $db->query('SELECT *
//FROM typesexcursion t
//INNER JOIN excursions e ON t.idType = e.idType
//INNER JOIN lieux l ON t.idLieuDepart = l.idLieu
//WHERE l.nomLieu LIKE "%'.$_POST['motCle'].'%" OR t.nomExcursion LIKE "%'.$_POST['motCle'].'%" OR t.descriptionType LIKE "%'.$_POST['motCle'].'%"') or die(print_r($db->errorInfo()));
//
//            //Test du retour de la base de données
//
//            $testAffichageRechercheMotCle = $affichageRechercheMotCle->rowCount();
//            if($testAffichageRechercheMotCle>0){
//                foreach ($affichageRechercheMotCle as $row) {
//
//                    //Requête : contage du nombre d'inscrits selon l'idExcursion
//                    $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits FROM inscriptions WHERE idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
//                    $testRetourBase = $countexcursion->rowCount();
//                    if($testRetourBase>0){
//                        foreach ($countexcursion as $row2){
//                            $nbreParticipantsInscrits = $row2['totalinscrits'];
//                        }
//                    }else{
//                        $nbreParticipantsInscrits = 0;
//                    }
//
//                    $rechercheIdExcursion = $row['idExcursion'];
//                    $rechercheDateDepart = $row['dateDepart'];
//                    $rechercheDateRetour = $row['dateRetour'];
//                    $rechercheIdType = $row['idType'];
//                    $rechercheNomExcursion = $row['nomExcursion'];
//
//                    //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
//                    $nbreParticipantsRestants = '';
//                    $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;
//
//                    //S'il reste des inscriptions pour l'idExcursion
//                    if($nbreParticipantsRestants > 0){
//                        echo "<div class=\"carteConsultation\"> <!-- MODIF CLASS -> class carteDetail -->
//                <form action='detailsExcursion.php' method='post' class='formRecherche'>
//                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$rechercheIdExcursion."' type='hidden' />";
//
//                        //Requete chemin photo
//                        $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description FROM photos p INNER JOIN illustrations i ON p.idPhoto = i.idPhoto INNER JOIN excursions e ON i.idType = e.idType  WHERE e.idExcursion = '.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
//                        $cheminPhoto = '';
//                        $descriptionPhoto = '';
//                        foreach ($affichagephoto as $row2){
//                            $cheminPhoto = $row2['cheminPhoto'];
//                            $descriptionPhoto = $row2['description'];
//                        }
//                        //Affichage du détail de la date et de l'heure
//                        $dateDepart = $row['dateDepart'];
//                        $dateRetour = $row['dateRetour'];
//                        include "affichageDateHeure.php";
//
//                        echo "<div class='hexagone'>
//                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
//                    </div>
//                    <h3>".$rechercheNomExcursion." (".$rechercheIdExcursion.")</h3>
//                    <p class='detailConsultation'>
//                    <span class=\"libelle\">Lieu : </span>";
//
//                        $nomLieuRetour = $db->query('SELECT nomLieu FROM lieux l INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee INNER JOIN excursions e ON t.idType = e.idType WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
//                        foreach($nomLieuRetour as $row4){
//                            echo $row4['nomLieu'];
//                        }
//
//                        echo "<br/>
//                    <span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
//                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
//                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
//                    </p>
//                    <p class='alerte'>";
//
//                        echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";
//
//                        echo "</p>
//                <input class='boutonConsult' type='submit' value='Détails' />
//                </form>
//            </div>";
//                    }
//                }
//            }else{
//                echo "<hr/>Pas d'excursion disponible pour le mot clé choisi";
//            }
        }else if(isset($_POST['menuRechercheLieux']) && $_POST['menuRechercheLieux'] > 0 && $_POST['menuRechercheLieux'] != "all"){
        //echo $_POST['menuRechercheLieux'];
        //Requête d'affichage
        $date = date("Y-m-d H:i:s");
        $affichageRechercheMotCle = $db->query('SELECT *
                                                            FROM typesexcursion t
                                                                INNER JOIN excursions e ON t.idType = e.idType    
                                                                INNER JOIN lieux l ON t.idLieuArrivee = l.idLieu
                                                            WHERE t.idLieuArrivee ='.$_POST['menuRechercheLieux']) or die(print_r($db->errorInfo()));

        //Test du retour de la base de données

        $testAffichageRechercheMotCle = $affichageRechercheMotCle->rowCount();
        if($testAffichageRechercheMotCle>0){
            foreach ($affichageRechercheMotCle as $row) {

                //Requête : contage du nombre d'inscrits selon l'idExcursion
                $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits 
                                                        FROM inscriptions 
                                                        WHERE idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
                $testRetourBase = $countexcursion->rowCount();
                if($testRetourBase>0){
                    foreach ($countexcursion as $row2){
                        $nbreParticipantsInscrits = $row2['totalinscrits'];
                    }
                }else{
                    $nbreParticipantsInscrits = 0;
                }

                $rechercheIdExcursion = $row['idExcursion'];
                $rechercheDateDepart = $row['dateDepart'];
                $rechercheDateRetour = $row['dateRetour'];
                $rechercheIdType = $row['idType'];
                $rechercheNomExcursion = $row['nomExcursion'];

                //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
                $nbreParticipantsRestants = '';
                $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;

                //S'il reste des inscriptions pour l'idExcursion
                if($nbreParticipantsRestants > 0){
                    echo "<div class=\"carteRecherche\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formAffichRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$rechercheIdExcursion."' type='hidden' />";

                    //Requete chemin photo
                    $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description 
                                                            FROM photos p 
                                                                INNER JOIN illustrations i ON p.idPhoto = i.idPhoto 
                                                                INNER JOIN excursions e ON i.idType = e.idType  
                                                            WHERE e.idExcursion = '.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    $cheminPhoto = '';
                    $descriptionPhoto = '';
                    foreach ($affichagephoto as $row2){
                        $cheminPhoto = $row2['cheminPhoto'];
                        $descriptionPhoto = $row2['description'];
                    }
                    //Affichage du détail de la date et de l'heure
                    $dateDepart = $row['dateDepart'];
                    $dateRetour = $row['dateRetour'];
                    include "affichageDateHeure.php";

                    echo "<div class='hexagone2'>
                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
                    </div>
                    <div class='texteRecherche'>
                    <h3>".$rechercheNomExcursion."</h3>
                    <p class='detailConsultation'>
                    <span class=\"libelle\">Lieu de départ : </span>";

                    $nomLieuDepart = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuDepart 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    foreach($nomLieuDepart as $row4){
                        echo $row4['nomLieu'];
                    }

                    echo "<br/><span class=\"libelle\">Lieu d'arrivée : </span>";

                    $nomLieuArrivee = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                    foreach($nomLieuArrivee as $row5){
                        echo $row5['nomLieu'];
                    }

                    echo "<br/>
                    <span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
                    </p>
                    </div>
                    <div class='alerteRecherche'>
                    <p class='alerte2'>";

                    echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";

                    echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
                </div>
            </div>";
                }
            }
        }else {
            echo "<section class='verif'>Pas d'excursion disponible pour le lieu d'arrivée choisi.</section>";
        }
        }else if(isset($_POST['menuRechercheLieux']) && $_POST['menuRechercheLieux'] > 0 && $_POST['menuRechercheLieux'] == "all"){
                //echo $_POST['menuRechercheLieux'];
                //Requête d'affichage
                $date = date("Y-m-d H:i:s");
                $affichageRechercheMotCle = $db->query('SELECT *
                                                                    FROM typesexcursion t
                                                                        INNER JOIN excursions e ON t.idType = e.idType    
                                                                        INNER JOIN lieux l ON t.idLieuArrivee = l.idLieu') or die(print_r($db->errorInfo()));

                //Test du retour de la base de données

                $testAffichageRechercheMotCle = $affichageRechercheMotCle->rowCount();
                if($testAffichageRechercheMotCle>0){
                    foreach ($affichageRechercheMotCle as $row) {

                        //Requête : contage du nombre d'inscrits selon l'idExcursion
                        $countexcursion = $db->query('SELECT count(idParticipant) AS totalinscrits 
                                                                    FROM inscriptions 
                                                                    WHERE idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
                        $testRetourBase = $countexcursion->rowCount();
                        if($testRetourBase>0){
                            foreach ($countexcursion as $row2){
                                $nbreParticipantsInscrits = $row2['totalinscrits'];
                            }
                        }else{
                            $nbreParticipantsInscrits = 0;
                        }

                        $rechercheIdExcursion = $row['idExcursion'];
                        $rechercheDateDepart = $row['dateDepart'];
                        $rechercheDateRetour = $row['dateRetour'];
                        $rechercheIdType = $row['idType'];
                        $rechercheNomExcursion = $row['nomExcursion'];

                        //Calcul du nombre d'inscriptions disponibles pour l'idExcursion
                        $nbreParticipantsRestants = '';
                        $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $nbreParticipantsInscrits;

                        //S'il reste des inscriptions pour l'idExcursion
                        if($nbreParticipantsRestants > 0){
                            echo "<div class=\"carteRecherche\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formAffichRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$rechercheIdExcursion."' type='hidden' />";

                            //Requete chemin photo
                            $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description 
                                                                        FROM photos p 
                                                                            INNER JOIN illustrations i ON p.idPhoto = i.idPhoto 
                                                                            INNER JOIN excursions e ON i.idType = e.idType  
                                                                        WHERE e.idExcursion = '.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                            $cheminPhoto = '';
                            $descriptionPhoto = '';
                            foreach ($affichagephoto as $row2){
                                $cheminPhoto = $row2['cheminPhoto'];
                                $descriptionPhoto = $row2['description'];
                            }
                            //Affichage du détail de la date et de l'heure
                            $dateDepart = $row['dateDepart'];
                            $dateRetour = $row['dateRetour'];
                            include "affichageDateHeure.php";

                            echo "<div class='hexagone2'>
                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
                    </div>
                    <div class='texteRecherche'>
                    <h3>".$rechercheNomExcursion."</h3>
                    <p class='detailConsultation'>
                    <span class=\"libelle\">Lieu de départ : </span>";

                            $nomLieuDepart = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuDepart 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                            foreach($nomLieuDepart as $row4){
                                echo $row4['nomLieu'];
                            }

                            echo "<br/><span class=\"libelle\">Lieu d'arrivée : </span>";

                            $nomLieuArrivee = $db->query('SELECT nomLieu 
                                                        FROM lieux l 
                                                            INNER JOIN typesexcursion t ON l.idLieu = t.idLieuArrivee 
                                                            INNER JOIN excursions e ON t.idType = e.idType 
                                                        WHERE e.idExcursion ='.$rechercheIdExcursion) or die(print_r($db->errorInfo()));
                            foreach($nomLieuArrivee as $row5){
                                echo $row5['nomLieu'];
                            }

                            echo "<br/>
                    <span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
                    </p>
                    </div>
                    <div class='alerteRecherche'>
                    <p class='alerte2'>";

                            echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";

                            echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
                </div>
            </div>";
                        }
                    }
                }else {
                    echo "<section class='verif'>Pas d'excursion disponible pour le lieu d'arrivée choisi.</section>";
                }
            }else{
            echo "<section class='verif'>Aucun champ renseigné.</section>";
            }

    ?>

</section>

</body>
</html>

