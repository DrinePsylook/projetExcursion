<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="designProjet.css"/>
    <title>Formulaire Inscription</title>
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
//Connexion BD
include('requeteConnexionBD.php');

//Requête de suppression
echo "Avant suppression : ".$_POST['deleteIdExcursion']."/".$_POST['deleteIdParticipant']."<hr/>";
if(isset($_POST['deleteIdExcursion']) && isset($_POST['deleteIdParticipant'])){
    $supprimer = $db->query('DELETE FROM inscriptions WHERE idExcursion ='.$_POST['deleteIdExcursion'].' AND idParticipant = '.$_POST['deleteIdParticipant']) or die(print_r($db->errorInfo()));
    echo "Inscription ".$_POST['deleteIdExcursion']."/".$_POST['deleteIdParticipant']." supprimée avec succès.<hr/>";
}else{
    echo "Pas d'idExcursion, ni d'idParticipant. Pas de suppression possible.<hr/>";
}
