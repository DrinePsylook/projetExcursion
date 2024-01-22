<?php
include('javascript.js'); //menu vers les liens js
?>

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

    <section id="accueil"  class="espaceMenu"> <!--  permet au menu de ne pas passer au-dessus de la première ancre-->
    </section>

    <section class="pgDetail">

        <div class="galerieExcursion">
            <?php
            //Requête de connexion bd
            include "requeteConnexionBD.php";

            //Requête d'affichage photos
            $affichagephotos = $db->query('SELECT p.cheminPhoto, p.description 
                                                    FROM photos p 
                                                        INNER JOIN illustrations i 
                                                            ON p.idPhoto = i.idPhoto 
                                                        INNER JOIN excursions e 
                                                            ON i.idType = e.idType  
                                                    WHERE e.idExcursion = '.$_POST['inputAffichageDetail']) or die(print_r($db->errorInfo()));
            foreach ($affichagephotos as $row){
                echo "<p class=\"imgExcursion\">
                        <a href='".$row['cheminPhoto']."'>
                        <img src=\"".$row['cheminPhoto']."\" alt=\"".$row['description']."\" ></a>
                    </p>";
            }
            ?>

        </div>

        <div class="alignDetail">
            <div class="textDetail1">
                <?php //count(e.idExcursion) AS NbreIdExcursion,
                //Requête d'affichage du détail de l'excursion
                $affichagedetail = $db->query('SELECT DISTINCT g.nomGuide, g.prenomGuide, t.nomExcursion, t.descriptionType, t.tarif, t.idLieuDepart, 
                                                t.idLieuArrivee, t.planCircuit, e.dateDepart, e.dateRetour, e.idExcursion, t.nbreMaxParticipants, r.nomRegion, li.nomLieu
                                                    FROM typesexcursion t
                                                        INNER JOIN lieux li ON t.idLieuDepart = li.idLieu
                                                        INNER JOIN regions r ON li.idRegion = r.idRegion
                                                        INNER JOIN excursions e ON t.idType = e.idType                                                        
                                                        INNER JOIN directions d ON e.idExcursion = d.idExcursion
                                                        INNER JOIN guides g ON d.numLicenceGuide = g.numLicenceGuide
                                                    WHERE e.idExcursion = '.$_POST['inputAffichageDetail']) or die(print_r($db->errorInfo()));
                foreach ($affichagedetail as $row2){

                    //Requête de count
                    $count = $db->query('SELECT count(idExcursion) AS NbreIdExcursion FROM inscriptions WHERE idExcursion ='.$_POST['inputAffichageDetail']) or die(print_r($db->errorInfo()));
                    if($count->rowCount()>0){
                        foreach ($count as $row3){
                            $placesdisponibles = $row2['nbreMaxParticipants'] - $row3['NbreIdExcursion'];
                        }
                    }else{
                        $placesdisponibles = $row2['nbreMaxParticipants'];
                    }

                    echo "<form action='FormulaireInscription.php' method='post'>
                            <input id='inputDetailsExcursion' name='inputDetailsExcursion' value='".$_POST['inputAffichageDetail']."' type='hidden' />
                            <h2>".$row2['nomExcursion']."</h2><br/> <!--le nom de l'excursion-->";

                    //Requête nomLieuArrivee
                    $nomLieuArrivee = $db->query('SELECT nomLieu FROM lieux WHERE idLieu = '.$row2['idLieuArrivee']) or die(print_r($db->errorInfo()));
                    foreach ($nomLieuArrivee as $row4){
                        $nomLieuArrivee = $row4['nomLieu'];
                    }

                    //Affichage du détail de la date et de l'heure
                    $dateDepart = $row2['dateDepart'];
                    $dateRetour = $row2['dateRetour'];
                    include "affichageDateHeure.php";

                    echo "<span class=\"libelle\">Lieu : </span>".$nomLieuArrivee."<br/> <!--lieu d'arrivée-->
                            <span class=\"libelle\">Description : </span>".$row2['descriptionType']."<br/>
                            <span class=\"libelle\">Guide : </span>".$row2['nomGuide']." ".$row2['prenomGuide']."<br/>
                            <span class=\"libelle\">Lieu de départ : </span>".$row2['nomLieu'] . ", ".$row2['nomRegion']."<br/>
                            <span class=\"libelle\">Date et heure de départ : </span>".$jourDepart."/".$moisDepart."/".$anneeDepart." à ".$heureDepart."h".$minuteDepart."<br/>
                            <span class=\"libelle\">Date et heure de retour : </span>".$jourRetour."/".$moisRetour."/".$anneeRetour." à ".$heureRetour."h".$minuteRetour."<br/>
                            <span class=\"libelle\">Nombre de participants maximum : </span>".$row2['nbreMaxParticipants']."<br/>
                            <span class=\"libelle\">Places disponibles : </span>".$placesdisponibles."<br/>
            </div>
            <div class='textDetail'>
                <a href='".$row2['planCircuit']."'>
                <img class=\"planExcu\" src=\"".$row2['planCircuit']."\" /></a><br/>
    
                <p class=\"tarif\">Tarif : ".$row2['tarif']."€</p>
    
                <p class=\"reserver\"><button type=\"submit\" class='boutonConsult2'>Réserver</button></p>
                </form>
            </div>
        </div>";
            }
            ?>


    <div id="modale" class="modal">
        <span class="close">&times;</span>
            <div class="modal-content">
                <img src="" alt="">
            </div>
    </div>

    </section>
</body>
</html>
