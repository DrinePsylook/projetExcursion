<div class="recherche">
    <h2>Recherche</h2>
    <p>Choississez votre option de recherche :</p>
    <p class="rechercheFonction">
        <span id="recherChoix1">Par ville de départ</span>
        <span id="recherChoix2">Par date</span>
        <span id="recherChoix3">Par lieu d'excursions</span>
    </p>

        <div id="contentRecherchVille" class="divChoix">
            <form action="resultatRecherche.php" method="post" class="formulaireRecherche">
                <select name="rechercheDepart" id="rechercheDepart" class="rechercheDepart">
<!--menu déroulant pour le choix de la ville au départ de-->
                    <option>Choisissez une option</option>
                    <option value="departColmar">Départ Colmar</option>
                    <option value="departMulhouse">Départ Mulhouse</option>
                </select>
                <button type="submit" class="boutonConsult">Valider</button>
            </form>
        </div>

    <div id="contentRecherchDate" class="divChoix">
        <form action="resultatRecherche.php" method="post" class="formulaireRecherche">
            <select name="rechercheMois" id="rechercheMois" class="parMois">
    <!--Menu déroulant pour choisir le mois-->
                <option value=" ">--Mois--</option>
                <option value="01">Janvier</option>
                <option value="02">Février</option>
                <option value="03">Mars</option>
                <option value="04">Avril</option>
                <option value="05">Mai</option>
                <option value="06">Juin</option>
                <option value="07">Juillet</option>
                <option value="08">Août</option>
                <option value="09">Septembre</option>
                <option value="10">Octobre</option>
                <option value="11">Novembre</option>
                <option value="12">Décembre</option>
            </select>
            <select name="rechercheAnnee" id="rechercheAnnee" class="parAn">
    <!--Menu déroulant pour choisir l'année'-->
                <option value=" ">--Année--</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
            <button type="submit" class="boutonConsult">Valider</button>
        </form>
    </div>

    <div id="contentRecherchLieu" class="divChoix">
        <form action="resultatRecherche.php" method="post" id="formLieux" class="formulaireRecherche">
            <select id="menuRechercheLieux" name="menuRechercheLieux" class="rechercheDepart">
                <option value="">Choisissez un lieu</option>
                <?php
                    $menuRechercheLieux = $db->query('SELECT * FROM lieux ORDER BY nomLieu') or die(print_r($db->errorInfo()));
                    foreach($menuRechercheLieux as $row){
                        echo "<option value='".$row['idLieu']."'>".$row['nomLieu']."</option>";
                    }
                ?>
                <option value="all">Tous les lieux</option>
            </select>
            <button type="submit" class="boutonConsult">Valider</button>
        </form>
    </div>
<!--    <h3>Par mots clés</h3>-->
<!--    <div>-->
<!--        <form action="resultatRecherche.php" method="post" id="formMotCle">-->
<!--            <input type="text" id="motCle" name="motCle" placeholder="Mot Clé" class="rechercheDepart">-->
<!--            <button type="submit" class="boutonConsult">Valider</button>-->
<!--        </form>-->
<!--    </div>-->
</div>
