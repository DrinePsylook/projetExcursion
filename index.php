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

        <section class="ancre1">
            <?php
            include('ancreAccueil.php'); //menu de navigation unique en php//
            ?>
        </section>

        <section id="consultation"  class="espaceMenu"> <!--  permet au menu de ne pas passer au-dessus de la première ancre-->
        </section>

        <section class="ancre2">
            <?php
            include('ancreConsultation.php'); //menu de navigation unique en php//
            ?>
        </section>

        <section  class="espaceMenu"> <!--  permet au menu de ne pas passer au-dessus de la première ancre-->
<!--CODE ICI-->
            <div id="rechercher" ></div> <!--la div doit rester au-dessus de la fin de section pour l'ancre-->
        </section>

        <section class="ancre3">
            <?php
            include('ancreRecherche.php'); //menu de navigation unique en php//
            ?>
        </section>
        <section  class="espaceMenu"> <!--  permet au menu de ne pas passer au-dessus de la première ancre-->
            <!--CODE ICI-->
        </section>
    </body>
</html>
