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
if(isset($_POST['email'])){
    //echo $_POST['email']."<hr/>";

    //include de la Requête de connexion BD
    include "requeteConnexionBD.php";

    //Requête de vérification d'email
    $verifmail = $db->query('SELECT * FROM participants WHERE mailParticipant =\''.$_POST['email'].'\'') or die(print_r($db->errorInfo()));
    if($verifmail->rowCount() > 0){
        foreach ($verifmail as $row){
            $verifinscription = $db->query('SELECT * FROM inscriptions') or die(print_r($db->errorInfo()));
                foreach($verifinscription as $row2){
                    $boolverifinscription=TRUE;
                    if($row2['idExcursion'] == $_POST['inputFormulaireInscription'] && $row2['idParticipant'] == $row['idParticipant']){
                        $boolverifinscription=FALSE;
                    }
                }

                if($boolverifinscription==TRUE){
                    echo "<section class=\"verif\">
             <!--       <form action='FormulaireInscription.php' method='post'><p>Bonjour ".$row['prenomParticipant']." ".$row['nomParticipant']." (idParticipant :".$row['idParticipant']." / idExcursion : ".$_POST['inputFormulaireInscription'].")</p> -->
                    <form action='FormulaireInscription.php' method='post'><p>Bonjour ".$row['prenomParticipant']." ".$row['nomParticipant']."</p>
                    <input id='inputVerifMailIdExcursion' name='inputVerifMailIdExcursion' value='".$_POST['inputFormulaireInscription']."' type='hidden' />
                    <input id='inputVerifMailIdParticipant' name='inputVerifMailIdParticipant' value='".$row['idParticipant']."' type='hidden' />
                    <input id='inputVerifMailPrenomParticipant' name='inputVerifMailPrenomParticipant' value='".$row['prenomParticipant']."' type='hidden' />
                    <input id='inputVerifMailNomParticipant' name='inputVerifMailNomParticipant' value='".$row['nomParticipant']."' type='hidden' />
                    <input id='inputVerifMailNumTelParticipant' name='inputVerifMailNumTelParticipant' value='".$row['numTelParticipant']."' type='hidden' />
                    <input id='inputVerifMailMailParticipant' name='inputVerifMailMailParticipant' value='".$row['mailParticipant']."' type='hidden' />
                    <input type='submit' value='Continuer vers le formulaire' class=\"boutonformu\" />
                    </form>
                    </section>";
                }else{
                    echo "<section class=\"verif\">L'adresse ".$_POST['email']." est déjà engagée sur cette excursion.<hr/>";
                    echo "<form action='FormulaireInscription.php' method='post'>
                                <input id='inputVerifMailIdExcursion' name='inputVerifMailIdExcursion' value='".$_POST['inputFormulaireInscription']."' type='hidden' />
                                <input type='submit' value='Retour au formulaire' class=\"boutonformu\"/>
                                </form>
                          </section>";
                }
        }
    }else if(!empty($_POST['email'])){
        echo "<section class=\"verif\">
                <form action='FormulaireInscription.php' method='post'><p>".$_POST['email']." ne correspond à aucun email enregistré.</p>
                    <input id='inputVerifMailIdExcursion' name='inputVerifMailIdExcursion' value='".$_POST['inputFormulaireInscription']."' type='hidden' />
                    <input id='inputVerifMailMailParticipant' name='inputVerifMailMailParticipant' value='".$_POST['email']."' type='hidden' />
                    <input type='submit' value='Continuer vers le formulaire' class=\"boutonformu\"/>
                    </form>
                    </section>";
    }else{
        echo "<section class=\"verif\">
                <form action='FormulaireInscription.php' method='post'><p>Aucun mail saisi.</p>
                    <input id='inputVerifMailIdExcursion' name='inputVerifMailIdExcursion' value='".$_POST['inputFormulaireInscription']."' type='hidden' />                    
                    <input type='submit' value='Retour vers le formulaire' class=\"boutonformu\"/>
                    </form>
                </section>";
    }

}else{
    //echo "<section class=\"verif\">Aucun mail saisi, isset(post email) faux</section>";
    echo "<section class=\"verif\">
                <form action='FormulaireInscription.php' method='post'><p>Aucun mail saisi.</p>
                    <input id='inputVerifMailIdExcursion' name='inputVerifMailIdExcursion' value='".$_POST['inputFormulaireInscription']."' type='hidden' />                    
                    <input type='submit' value='Retour vers le formulaire' class=\"boutonformu\"/>
                    </form>
                </section>";
}
?>
</body>
</html>
