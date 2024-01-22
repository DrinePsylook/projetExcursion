<div class="consult">
    <h2>Propositions d'excursion</h2>

    <div id="content" class="cartes">
    <?php

    //Connexion à la base de données
include "requeteConnexionBD.php";


    //Requête d'affichage
    $date = date("Y-m-d H:i:s");
    $resultat = $db->query('SELECT count(insc.idParticipant) AS totalparticipants, e.idExcursion, t.nomExcursion, e.dateDepart, e.dateRetour, t.idLieuDepart, t.idLieuArrivee, t.tarif, t.nbreMaxParticipants FROM inscriptions insc INNER JOIN excursions e ON insc.idExcursion = e.idExcursion INNER JOIN typesexcursion t ON e.idType = t.idType WHERE dateDepart > "'.$date.'" GROUP BY e.idExcursion') or die(print_r($db->errorInfo()));
    $cpt=0;
    foreach ($resultat as $row) {
        if($cpt < 3){
            $nbreParticipantsRestants = '';
            $nbreParticipantsRestants = $row['nbreMaxParticipants'] - $row['totalparticipants'];
            if($nbreParticipantsRestants > 0){
                echo "<div class=\"carteConsultation\"> <!-- MODIF CLASS -> class carteDetail -->
                <form action='detailsExcursion.php' method='post' class='formRecherche'>
                <input id='inputAffichageDetail' name='inputAffichageDetail' value='".$row['idExcursion']."' type='hidden' />";


                //Requete chemin photo
                $affichagephoto = $db->query('SELECT p.cheminPhoto, p.description FROM photos p INNER JOIN illustrations i ON p.idPhoto = i.idPhoto INNER JOIN excursions e ON i.idType = e.idType  WHERE e.idExcursion = '.$row['idExcursion']) or die(print_r($db->errorInfo()));
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

                echo "<div class='hexagone'>
                    <img class='imgCartes' src='".$cheminPhoto."' alt='".$descriptionPhoto."'>
                    </div>
                    <h3>".$row['nomExcursion']."</h3>
                    <p class='detailConsultation'>";

                    echo "<span class=\"libelle\">Lieu de départ : </span>";
                //Requête nomLieuDepart
                $nomLieuDepart = $db->query('SELECT nomLieu FROM lieux WHERE idLieu = '.$row['idLieuDepart']) or die(print_r($db->errorInfo()));
                foreach ($nomLieuDepart as $row4) {
                    $nomLieu = $row4['nomLieu'];
                }
                echo $nomLieu."<br/>";

                    echo "<span class=\"libelle\">Lieu d'arrivée : </span>";
                //Requête nomLieuArrivee
                $nomLieuArrivee = $db->query('SELECT nomLieu FROM lieux WHERE idLieu = '.$row['idLieuArrivee']) or die(print_r($db->errorInfo()));
                foreach ($nomLieuArrivee as $row5) {
                    $nomLieu = $row5['nomLieu'];
                }
                echo $nomLieu."<br/>";

                echo "<span class=\"libelle\">Date de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart." <!--détails à remplir en php -->
                    <br/><span class=\"libelle\">Date de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."
                    <br><span class=\"libelle\">Tarif : </span>".$row['tarif']." €
                    </p>
                    <p class='alerte'>";

                echo "Il ne reste plus que ".$nbreParticipantsRestants." places<br/>(Participants max : ".$row['nbreMaxParticipants'].")";

                echo "</p>
                <input class='boutonConsult' type='submit' value='Détails' />                    
                </form>
            </div>            
            ";
                $cpt++;
            }

        }else{
            break;
        }
    }
    ?>
    </div>
</div>

