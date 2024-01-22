/*Changement de couleur des onglets de navigation*/

$('#onglet1').on("click", function() {
        $('.active').removeClass('active'); <!--Si on a cliqué avant sur un autre onglet, on les enlève tous pour ne pas avoir à chercher lequel a la class "active" -->
        $(this).addClass('active');
});

    $('#onglet2').on("click", function() {
    $('.active').removeClass('active');
    $(this).addClass('active');
});

    $('#onglet3').on("click", function() {
    $('.active').removeClass('active');
    $(this).addClass('active');
});

/*--------------------------------------------------------------------------------------------------------------------*/
/*apparition de la recherche en fonction des choix*/
/*--------------------------------------------------------------------------------------------------------------------*/

$('#recherChoix1').on("click", function() {
    $('.divChoix').hide(); <!--on prend toutes les div avec class "divChoix"-->
    $('#contentRecherchVille').show(); <!--on affiche la div avec l'id "contentRecherchVille"-->
    $('.choixActif').removeClass('choixActif'); <!--Si on a cliqué avant sur un autre onglet, on les enlève tous pour ne pas avoir à chercher lequel a la class "active" -->
    $(this).addClass('choixActif');
});

$('#recherChoix2').on("click", function() {
    $('.divChoix').hide();
    $('#contentRecherchDate').show();
    $('.choixActif').removeClass('choixActif'); <!--Si on a cliqué avant sur un autre onglet, on les enlève tous pour ne pas avoir à chercher lequel a la class "active" -->
    $(this).addClass('choixActif');
});

$('#recherChoix3').on("click", function() {
    $('.divChoix').hide();
    $('#contentRecherchLieu').show();
    $('.choixActif').removeClass('choixActif'); <!--Si on a cliqué avant sur un autre onglet, on les enlève tous pour ne pas avoir à chercher lequel a la class "active" -->
    $(this).addClass('choixActif');
});