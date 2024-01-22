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
<?php
//Requête de connexion
include "requeteConnexionBD.php";

//Rajout testdate
$infoexcursion = $db->query('SELECT * FROM excursions WHERE idExcursion = '.$_POST['inputFormIdExcursion']) or die(print_r($db->errorInfo()));
foreach($infoexcursion as $row9){
    $recupdatedepart = $row9['dateDepart'];
    $recupdateretour = $row9['dateRetour'];
}
//Fin Rajout testdate

if(!empty($_POST['inputFormIdParticipant']) && $_POST['inputFormIdParticipant'] > 0){

    $verifinscription = $db->query('SELECT * FROM inscriptions') or die(print_r($db->errorInfo()));
    $validinscription = 1;
    $testbool = FALSE;
    
    foreach($verifinscription as $row){
        if($row['idExcursion'] == $_POST['inputFormIdExcursion'] && $row['idParticipant'] == $_POST['inputFormIdParticipant']){
            $validinscription = 2;
            $testbool = TRUE;
        }
    }

    if($testbool == FALSE){
        //RAJOUT testdate//
    //On teste si le participant n'est pas inscrit à une autre excursion dont les dates se chevaucheraient avec l'ecursion actuelle
    $verifdate = $db->query("SELECT * FROM participants p INNER JOIN inscriptions i ON p.idParticipant = i.idParticipant INNER JOIN excursions e ON i.idExcursion = e.idExcursion INNER JOIN typesexcursion t ON e.idType = t.idType WHERE i.idParticipant = " . $_POST['inputFormIdParticipant'] . " AND (e.dateDepart BETWEEN '" . $recupdatedepart . "' AND '" . $recupdateretour . "' OR e.dateRetour BETWEEN '" . $recupdatedepart . "' AND '" . $recupdateretour . "')") or die(print_r($db->errorInfo()));
    if ($verifdate->rowCount() > 0) {
        foreach ($verifdate as $row8) {
            $datedepart = $row8['dateDepart'];
            $dateretour = $row8['dateRetour'];
            $nomexcursion = $row8['nomExcursion'];
        }
        $validinscription = 3;
    }
    //Fin RAJOUT testdate//
    }


    if($validinscription == 1){

        //echo "L'inscription est possible, la requête peut être faite";
        $creationinscription = $db->prepare('INSERT INTO inscriptions(idExcursion, idParticipant, dateInscription) VALUES (:idExcursion, :idParticipant, :dateInscription)') or die(print_r($db->errorInfo()));

        $creationinscription->execute([
            'idExcursion' => $_POST['inputFormIdExcursion'],
            'idParticipant' => $_POST['inputFormIdParticipant'],
            'dateInscription' => date("Y-m-d H:i:s"),
        ]);
        echo "<section class=\"verif\">INSCRIPTION EFFECTUEE<br/>";
            $inscription = $db->query('SELECT * FROM inscriptions WHERE idExcursion='.$_POST['inputFormIdExcursion'].' AND idParticipant = '.$_POST['inputFormIdParticipant']) or die(print_r($db->errorInfo()));
            foreach($inscription as $row){

            include "enregistrement_confirmationpdf.php";
            }
            echo "<form action=\"index.php\">
                    <input type=\"submit\" value=\"Retour à l'index\" class=\"boutonformu\" />
                    </form>";
            echo "</section>";

    }else if($validinscription == 2){
        echo "<section class=\"verif\">L'inscription ne peut s'effectuer : vous vous êtes déjà inscrit à cette excursion.";
        echo "<form action=\"index.php\">
                    <input type=\"submit\" value=\"Retour à l'index\" class=\"boutonformu\" />
                </form>
                </section>";
    //Rajout testdate
    }else if($validinscription == 3) {
        echo "<section class=\"verif\">L'inscription ne peut s'effectuer : vous vous êtes inscrit à une autre excursion :<br/>" . $nomexcursion . " le " . $datedepart . " jusqu'au " . $dateretour . "<br/>";
        echo "<form action=\"index.php\">
                    <input type=\"submit\" value=\"Retour à l'index\" class=\"boutonformu\" />
                </form></section>";
    }
    //Fin Rajout testdate
}else{
    //On teste les valeurs renseignées dans le formulaire. Si au moins une valeur est nulle, on affiche le récap et on précise qu'il faut se réinscrire en remplissant tous les champs
    if(empty($_POST['nomParticipant']) || empty($_POST['prenomParticipant']) || empty($_POST['numTelParticipant']) || empty($_POST['mailParticipant'])){
      echo "<section class=\"verif\">Le formulaire est incomplet. Merci de remplir tous les champs demandés.</section>";
      echo "<form action='FormulaireInscription.php' method='post'>
                  <input id='invalidFormIdExcursion' name='invalidFormIdExcursion' value='".$_POST['inputFormIdExcursion']."' type='hidden' />
                  <input id='invalidFormNomParticipant' name='invalidFormNomParticipant' value='".$_POST['nomParticipant']."' type='hidden' />
                  <input id='invalidFormPrenomParticipant' name='invalidFormPrenomParticipant' value='".$_POST['prenomParticipant']."' type='hidden' />
                  <input id='invalidFormNumTelParticipant' name='invalidFormNumTelParticipant' value='".$_POST['numTelParticipant']."' type='hidden' />
                  <input id='invalidFormNumMailParticipant' name='invalidFormNumMailParticipant' value='".$_POST['numTelParticipant']."' type='hidden' />
                  <input type='submit' value='Retour au formulaire' class=\"boutonformu\" />
              </form>";
    }else{
      $dejainscrit = $db->query('SELECT * FROM participants WHERE nomParticipant="'.$_POST['nomParticipant'].'" AND prenomParticipant="'.$_POST['prenomParticipant'].'" AND numTelParticipant="'.$_POST['numTelParticipant'].'" AND mailParticipant="'.$_POST['mailParticipant'].'"') or die(print_r($db->errorInfo()));
        if($dejainscrit->rowCount()>0){
            echo "<br/>Bonjour ".$_POST['nomParticipant']." ".$_POST['prenomParticipant'].". Vous êtes déjà enregistré. L'inscription va s'effectuer.<hr/>";
        }else{
            $creationparticipant = $db->prepare('INSERT INTO participants(nomParticipant, prenomParticipant, numTelParticipant, mailParticipant) VALUES (:nomParticipant, :prenomParticipant, :numTelParticipant, :mailParticipant)') or die(print_r($db->errorInfo()));
            $creationparticipant->execute([
                'nomParticipant' => $_POST['nomParticipant'],
                'prenomParticipant' => $_POST['prenomParticipant'],
                'numTelParticipant' => $_POST['numTelParticipant'],
                'mailParticipant' => $_POST['mailParticipant'],
            ]);
        }
    }
//echo "fin enregistrement participant<hr/>".$_POST['mailParticipant'];
    //Récupération de l'idParticipant
    $recupidparticipant = $db->query('SELECT * FROM participants WHERE mailParticipant = "'.$_POST['mailParticipant'].'"') or die(print_r($db->errorInfo()));
    if($recupidparticipant->rowCount() > 0){
        foreach($recupidparticipant as $row){
            $recupidparticipant = $row['idParticipant'];
        }
    }

//echo "fin récup idParticipant créé".$recupidparticipant."<hr/>";
//echo "début test déjà inscrit<hr/>";
    //Test de l'existance d'une inscription
    $verifinsc = 1;
    $verifinscription = $db->query('SELECT * FROM inscriptions WHERE idExcursion ='.$_POST['inputFormIdExcursion'].' AND idParticipant ='.$recupidparticipant) or die(print_r($db->errorInfo()));
    if($verifinscription->rowCount()>0){
        $verifinsc = 2;
    }
//echo "début test chevauchement date<hr/>";
    //RAJOUT testdate//
    //On teste si le participant n'est pas inscrit à une autre excursion dont les dates se chevaucheraient avec l'ecursion actuelle
    $verifdate = $db->query("SELECT * FROM participants p INNER JOIN inscriptions i ON p.idParticipant = i.idParticipant INNER JOIN excursions e ON i.idExcursion = e.idExcursion INNER JOIN typesexcursion t ON e.idType = t.idType WHERE i.idParticipant = " . $recupidparticipant . " AND (e.dateDepart BETWEEN '" . $recupdatedepart . "' AND '" . $recupdateretour . "' OR e.dateRetour BETWEEN '" . $recupdatedepart . "' AND '" . $recupdateretour . "')") or die(print_r($db->errorInfo()));
    if ($verifdate->rowCount() > 0) {
        foreach ($verifdate as $row8) {
            $datedepart = $row8['dateDepart'];
            $dateretour = $row8['dateRetour'];
            $nomexcursion = $row8['nomExcursion'];
        }
        $verifinsc = 3;
    }
    //Fin RAJOUT testdate//

//echo $verifinsc;
    if($verifinsc == 1){
      $creationinscription = $db->prepare('INSERT INTO inscriptions(idExcursion, idParticipant, dateInscription) VALUES (:idExcursion, :idParticipant, :dateInscription)') or die(print_r($db->errorInfo()));
            $creationinscription->execute([
                'idExcursion' => $_POST['inputFormIdExcursion'],
                'idParticipant' => $recupidparticipant,
                'dateInscription' => date("Y-m-d H:i:s"),
            ]);
            echo "<section class=\"verif\">INSCRIPTION EFFECTUEE<br/>";
                $inscription = $db->query('SELECT * FROM inscriptions WHERE idExcursion='.$_POST['inputFormIdExcursion'].' AND idParticipant = '.$recupidparticipant) or die(print_r($db->errorInfo()));
            $idExcur = 0;
            $idPart = 0;
            $date = '';
            foreach($inscription as $row){    //
                include "enregistrement_confirmationpdf.php";
            }
            echo "<form action=\"index.php\">
                        <input type=\"submit\" value=\"Retour à l'index\" class=\"boutonformu\" />
                    </form>";
            echo "</section>";

    }else if($verifinsc == 2){
      echo "<section class=\"verif\">Vous êtes déjà inscrit à cette excursion.";
            echo "<form action=\"index.php\">
                        <input type=\"submit\" value=\"Retour à l'index\" class=\"boutonformu\" />
                    </form>
                    </section>";
    }else if($verifinsc == 3){
      echo "<section class=\"verif\">L'inscription ne peut s'effectuer : vous vous êtes inscrit à une autre excursion :<br/>" . $nomexcursion . " le " . $datedepart . " jusqu'au " . $dateretour . "<br/>";
            echo "<form action=\"index.php\">
                        <input type=\"submit\" value=\"Retour à l'index\" class=\"boutonformu\" />
                    </form></section>";
    }
}

?>
</body>
</html>
