<?php
include('javascript.js'); //menu vers les liens js
?>

<!DOCTYPE html>
<html lang="fr">
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
        <?php
        //Echo $_POST['inputDetailsExcursion'];
        include "requeteConnexionBD.php";
        ?>

        <?php
        if(!empty($_POST['inputDetailsExcursion'])){
            echo "<section class=\"invitMail\">
        <p><span class=\"libelle\">Déjà inscrit ? Veuillez saisir votre adresse e-mail :</span></p>
        <form class=\"dejaInscrit\" action=\"formulaireVerifMail.php\" method=\"post\">
            <input id=\"inputFormulaireInscription\" name=\"inputFormulaireInscription\" value=\"".$_POST['inputDetailsExcursion']."\" type=\"hidden\" />
    <input type=\"text\" id=\"email\" name=\"email\" placeholder=\"E-mail\" />
    <button type=\"submit\" class='boutonformu'>Valider</button>
    </form>
    </section>";
        }else{
            echo "<section class=\"invitMail\">
        <p><span class=\"libelle\">Déjà inscrit ? Veuillez saisir votre adresse e-mail :</span></p>
        <form class=\"dejaInscrit\" action=\"formulaireVerifMail.php\" method=\"post\">
            <input id=\"inputFormulaireInscription\" name=\"inputFormulaireInscription\" value=\"".$_POST['inputVerifMailIdExcursion']."\" type=\"hidden\" />
    <input type=\"text\" id=\"email\" name=\"email\" placeholder=\"E-mail\" />
    <button type=\"submit\" class=\"boutonformu\">Valider</button>
    </form>
    </section>";
        }
        ?>


    <section class="invitMail">
        <h2>Réservation</h2>
<?php
//Affichage de la zone "Déjà inscrit?" si l'idExcursion vient de detailsExcursion : $_POST['inputDetailsExcursion']
        if(isset($_POST['inputDetailsExcursion'])){
            //echo "Affichage de l'id venant du detailsExcursion".$_POST['inputDetailsExcursion']."<hr/>";
            //Requête affichage lieu, dates par rapport à l'idExcursion
            $affinfoform = $db->query('SELECT l.nomLieu, e.dateDepart, e.dateRetour
                                            FROM excursions e
                                            INNER JOIN typesexcursion t ON e.idType = t.idType
                                                INNER JOIN lieux l ON t.idLieuDepart = l.idLieu
                                            WHERE e.idExcursion = '.$_POST['inputDetailsExcursion']) or die(print_r($db->errorInfo())); //.$_POST['inputAffichageDetail']
            foreach ($affinfoform as $row){
                $lieu = $row['nomLieu'];
                $dateDepart = $row['dateDepart'];
                $dateRetour = $row['dateRetour'];
            }

            //
            include "affichageDateHeure.php";

            if($anneeDepart == $anneeRetour && $moisDepart == $moisRetour && $jourDepart == $jourRetour){
                echo "<p>Pour votre réservation à ".$lieu.", le ".$jourDepart."/".$moisDepart."/".$anneeDepart." de ".$heureDepart."h".$minuteDepart." à ".$heureRetour."h".$minuteRetour."</p>";
            }else{
                echo "<p>Pour votre réservation à ".$lieu.", du ".$jourDepart."/".$moisDepart."/".$anneeDepart." au ".$jourRetour."/".$moisRetour."/".$anneeRetour." :</p>";
            }

//Affichage de la zone "Déjà inscrit?" si l'idExcursion vient de formulaireVerifMail : $_POST['inputVerifMailIdExcursion']
        }else if(isset($_POST['inputVerifMailIdExcursion']) && $_POST['inputVerifMailIdExcursion'] !== ''){
            //echo "Affichage de l'id venant du formulaireVerifMail : ".$_POST['inputVerifMailIdExcursion']."<hr/>";
            //Requête affichage lieu, dates par rapport à l'idExcursion
            $affinfoform = $db->query('SELECT l.nomLieu, e.dateDepart, e.dateRetour
                                            FROM excursions e
                                            INNER JOIN typesexcursion t ON e.idType = t.idType
                                                INNER JOIN lieux l ON t.idLieuDepart = l.idLieu
                                            WHERE e.idExcursion = '.$_POST['inputVerifMailIdExcursion']) or die(print_r($db->errorInfo())); //.$_POST['inputAffichageDetail']
            foreach ($affinfoform as $row){
                $lieu = $row['nomLieu'];
                $dateDepart = $row['dateDepart'];
                $dateRetour = $row['dateRetour'];
            }

            //
            include "affichageDateHeure.php";

            if($anneeDepart == $anneeRetour && $moisDepart == $moisRetour && $jourDepart == $jourRetour){
                echo "<p>Pour votre réservation à ".$lieu.", le ".$jourDepart."/".$moisDepart."/".$anneeDepart." de ".$heureDepart."h".$minuteDepart." à ".$heureRetour."h".$minuteRetour."</p>";
            }else{
                echo "<p>Pour votre réservation à ".$lieu.", du ".$jourDepart."/".$moisDepart."/".$anneeDepart." au ".$jourRetour."/".$moisRetour."/".$anneeRetour." :</p>";
            }
        }else{
            //header('Location:index.php');
        }



?>
<?php
        if(isset($_POST['inputVerifMailIdExcursion']) && isset($_POST['inputVerifMailIdParticipant']) && isset($_POST['inputVerifMailPrenomParticipant']) && isset($_POST['inputVerifMailNomParticipant']) && isset($_POST['inputVerifMailNumTelParticipant'])){
//            echo "1er formulaire";
//            var_dump($_POST);
            echo"<form action=\"inscription.php\" method=\"post\" class=\"formulaire\" id=\"formulaire\">
            <input id='inputFormIdExcursion' name='inputFormIdExcursion' value='".$_POST['inputVerifMailIdExcursion']."' type='hidden' />
            <input id='inputFormIdParticipant' name='inputFormIdParticipant' value='".$_POST['inputVerifMailIdParticipant']."' type='hidden' />
            <label for=\"nomParticipant\"><span class=\"libelle\">Nom :</span> </label>
            <input type=\"text\" id=\"nomParticipant\" name=\"nomParticipant\" value='".$_POST['inputVerifMailNomParticipant']."' disabled /><br/>
            <label for=\"prenomParticipant\"><span class=\"libelle\">Prénom :</span> </label>
            <input type=\"text\" id=\"prenomParticipant\" name=\"prenomParticipant\"value='".$_POST['inputVerifMailPrenomParticipant']."' disabled /><br/>
            <label for=\"numTelParticipant\"><span class=\"libelle\">Téléphone :</span> </label>
            <input type=\"text\" id=\"numTelParticipant\" name=\"numTelParticipant\" value='".$_POST['inputVerifMailNumTelParticipant']."' disabled /><br/>
            <label for=\"mailParticipant\"><span class=\"libelle\">E-mail :</span> </label>
            <input type=\"text\" id=\"mailParticipant\" name=\"mailParticipant\" value='".$_POST['inputVerifMailMailParticipant']."' disabled /><br/>
            <p class=\"boutonReservation\"><!-- <button type=\"submit\" class=\"boutonformu\">Valider et ajouter une personne</button> -->
            <button type=\"submit\" class=\"boutonformu\">Valider votre réservation</button><br/><span id=\"erreurSaisie\"></span></p>
        </form>";
        }else if(!empty($_POST['inputDetailsExcursion']) && $_POST['inputDetailsExcursion']>0){
            echo "<form action=\"inscription.php\" method=\"post\" class=\"formulaire\" id=\"formulaire\">
            <input id='inputFormIdExcursion' name='inputFormIdExcursion' value='".$_POST['inputDetailsExcursion']."' type='hidden' />
            <label for=\"nomParticipant\"><span class=\"libelle\">Nom :</span> </label>
            <input type=\"text\" id=\"nomParticipant\" name=\"nomParticipant\"><br/>
            <label for=\"prenomParticipant\"><span class=\"libelle\">Prénom :</span> </label>
            <input type=\"text\" id=\"prenomParticipant\" name=\"prenomParticipant\"><br/>
            <label for=\"numTelParticipant\"><span class=\"libelle\">Téléphone :</span> </label>
            <input type=\"text\" id=\"numTelParticipant\" name=\"numTelParticipant\"><br/>
            <label for=\"mailParticipant\"><span class=\"libelle\">E-mail :</span> </label>
            <input type=\"text\" id=\"mailParticipant\" name=\"mailParticipant\" /><br/>
             <p class=\"boutonReservation\"><!--<button type=\"submit\"class=\"boutonformu\">Valider et ajouter une personne</button> -->
            <button type=\"submit\" class=\"boutonformu\">Valider votre réservation</button><br/><span id=\"erreurSaisie\"></span></p>
        </form>";
        }else
//            if($_POST['invalidFormIdExcursion'] !==0 || $_POST['invalidFormNomParticipant'] !==0 || $_POST['invalidFormPrenomParticipant'] !==0 || $_POST['invalidFormNumTelParticipant'] !==0 || $_POST['invalidFormMailParticipant'] !==0){
//            echo "<form action=\"inscription.php\" method=\"post\" class=\"formulaire\" id=\"formulaire\">
//            <input id='inputFormIdExcursion' name='inputFormIdExcursion' value='".$_POST['invalidFormIdExcursion']."' type='hidden' />
//            <label for=\"nomParticipant\"><span class=\"libelle\">Nom :</span></label>
//            <input type=\"text\" id=\"nomParticipant\" name=\"nomParticipant\"  value='".$_POST['invalidFormNomParticipant']."' /><br/>
//            <label for=\"prenomParticipant\"><span class=\"libelle\">Prénom :</span> </label>
//            <input type=\"text\" id=\"prenomParticipant\" name=\"prenomParticipant\"  value='".$_POST['invalidFormPrenomParticipant']."' /><br/>
//            <label for=\"numTelParticipant\"><span class=\"libelle\">Téléphone :</span> </label>
//            <input type=\"text\" id=\"numTelParticipant\" name=\"numTelParticipant\"  value='".$_POST['invalidFormNumTelParticipant']."' /><br/>
//            <label for=\"mailParticipant\"><span class=\"libelle\">E-mail :</span> </label>
//            <input type=\"text\" id=\"mailParticipant\" name=\"mailParticipant\" value='".$_POST['invalidFormMailParticipant']."' /><br/>
//            <p class=\"boutonReservation\"><button type=\"submit\" class=\"boutonformu\">Valider et ajouter une personne</button>
//            <button type=\"submit\" class=\"boutonformu\">Valider votre réservation</button><br/><span id=\"erreurSaisie\"></span></p>
//        </form>";
       // }else
        if(isset($_POST['inputVerifMailMailParticipant']) && $_POST['inputVerifMailMailParticipant'] > 0){
            echo "<form action=\"inscription.php\" method=\"post\" class=\"formulaire\" id=\"formulaire\">
            <input id='inputFormIdExcursion' name='inputFormIdExcursion' value='".$_POST['inputVerifMailIdExcursion']."' type='hidden' />
            <label for=\"nomParticipant\"><span class=\"libelle\">Nom :</span></label>
            <input type=\"text\" id=\"nomParticipant\" name=\"nomParticipant\"><br/>
            <label for=\"prenomParticipant\"><span class=\"libelle\">Prénom :</span> </label>
            <input type=\"text\" id=\"prenomParticipant\" name=\"prenomParticipant\"><br/>
            <label for=\"numTelParticipant\"><span class=\"libelle\">Téléphone :</span> </label>
            <input type=\"text\" id=\"numTelParticipant\" name=\"numTelParticipant\"><br/>
            <label for=\"mailParticipant\"><span class=\"libelle\">E-mail :</span> </label>
            <input type=\"text\" id=\"mailParticipant\" name=\"mailParticipant\" value='".$_POST['inputVerifMailMailParticipant']."' /><br/>
            <p class=\"boutonReservation\"><!-- <button type=\"submit\" class=\"boutonformu\">Valider et ajouter une personne</button> -->
            <button type=\"submit\" class=\"boutonformu\">Valider votre réservation</button><br/><span id=\"erreurSaisie\"></span></p>
        </form>";
        }else{
//            echo "2e formulaire";
            echo "<form action=\"inscription.php\" method=\"post\" class=\"formulaire\" id=\"formulaire\">
            <input id='inputFormIdExcursion' name='inputFormIdExcursion' value='".$_POST['inputVerifMailIdExcursion']."' type='hidden' />
            <label for=\"nomParticipant\"><span class=\"libelle\">Nom :</span></label>
            <input type=\"text\" id=\"nomParticipant\" name=\"nomParticipant\"><br/>
            <label for=\"prenomParticipant\"><span class=\"libelle\">Prénom :</span> </label>
            <input type=\"text\" id=\"prenomParticipant\" name=\"prenomParticipant\"><br/>
            <label for=\"numTelParticipant\"><span class=\"libelle\">Téléphone :</span> </label>
            <input type=\"text\" id=\"numTelParticipant\" name=\"numTelParticipant\"><br/>
            <label for=\"mailParticipant\"><span class=\"libelle\">E-mail :</span> </label>
            <input type=\"text\" id=\"mailParticipant\" name=\"mailParticipant\"  /><br/>
            <p class=\"boutonReservation\"><!-- <button type=\"submit\" class=\"boutonformu\">Valider et ajouter une personne</button> -->
            <button type=\"submit\" class=\"boutonformu\">Valider votre réservation</button><br/><span id=\"erreurSaisie\"></span></p>
        </form>";
        }


?>

    </section>
</body>
</html>

