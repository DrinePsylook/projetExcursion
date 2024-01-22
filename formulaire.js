$('#formulaire').on("submit", function(e){

    var nomFormulaire = $('#nomParticipant').val();
    var prenomFormulaire = $('#prenomParticipant').val();
    var numTelFormulaire = $('#numTelParticipant').val();
    var mailFormulaire = $('#mailParticipant').val();
    // var regex = /^[a-z0-9.]+@[a-z0-9]+$/;

    if(nomFormulaire == '' || prenomFormulaire == '' || numTelFormulaire == '' || mailFormulaire == ''){
        e.preventDefault();
        $('#erreurSaisie').css("color", "red");
        $('#erreurSaisie').html("Veuillez remplir tous les champs.");
    }
})