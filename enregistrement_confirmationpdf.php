<?php
include "fpdf/fpdf.php";

//Requête d'affichage des infos participant et excursion

//Inscription pour participant existant
//$_POST['inputFormIdExcursion']
//$_POST['inputFormIdParticipant']

//Inscription pour nouveau participant
//$_POST['inputFormIdExcursion']
//$recupIdParticipant

if(isset($_POST['inputFormIdExcursion']) && $_POST['inputFormIdExcursion'] > 0 && isset($_POST['inputFormIdParticipant']) && $_POST['inputFormIdParticipant'] > 0){
    $confirm = $db->query('SELECT * FROM participants p INNER JOIN inscriptions i ON p.idParticipant = i.idParticipant INNER JOIN excursions e ON i.idExcursion = e.idExcursion INNER JOIN typesexcursion t ON e.idType = t.idType INNER JOIN lieux l ON t.idLieuDepart = l.idLieu WHERE i.idParticipant ='.$_POST['inputFormIdParticipant'].' AND i.idExcursion ='.$_POST['inputFormIdExcursion'].'');
    foreach($confirm as $row){
        $idp = $row['idParticipant'];
        $ide = $row['idExcursion'];
        $nom = $row['nomParticipant'];
        $prenom = $row['prenomParticipant'];
        $tel = $row['numTelParticipant'];
        $mail = $row['mailParticipant'];
        $nomexcur = $row['nomExcursion'];
        $datea = $row['dateDepart'];
        $lieudepart = $row['nomLieu'];
        $dater = $row['dateRetour'];
        $datei = $row['dateInscription'];
        $tarif = $row['tarif'];
    }

}else if(isset($_POST['inputFormIdExcursion']) && $_POST['inputFormIdExcursion'] > 0 && $recupIdParticipant > 0){
    $confirm = $db->query('SELECT * FROM participants p INNER JOIN inscriptions i ON p.idParticipant = i.idParticipant INNER JOIN excursions e ON i.idExcursion = e.idExcursion INNER JOIN typesexcursion t ON e.idType = t.idType INNER JOIN lieux l ON t.idLieuDepart = l.idLieu  WHERE i.idParticipant ='.$recupIdParticipant.' AND i.idExcursion ='.$_POST['inputFormIdExcursion'].'');
    foreach($confirm as $row){
        $idp = $row['idParticipant'];
        $ide = $row['idExcursion'];
        $nom = $row['nomParticipant'];
        $prenom = $row['prenomParticipant'];
        $tel = $row['numTelParticipant'];
        $mail = $row['mailParticipant'];
        $nomexcur = $row['nomExcursion'];
        $datea = $row['dateDepart'];
        $lieudepart = $row['nomLieu'];
        $dater = $row['dateRetour'];
        $datei = $row['dateInscription'];
        $tarif = $row['tarif'];
    }

}

//Conversion de l'encodage pour toutes les variables
$titre = 'Module de réservation d\'excursions';
$titre = iconv('UTF-8','windows-1252', $titre );
$recap = 'Récapitulatif de l\'inscription';
$recap = iconv('UTF-8','windows-1252', $recap );
$nom = 'Nom: ' . $nom;
$nom = iconv('UTF-8','windows-1252', $nom );
$prenom = 'Prénom: ' . $prenom;
$prenom = iconv('UTF-8','windows-1252', $prenom );
$tel = 'Numéro de téléphone: ' . $tel;
$tel = iconv('UTF-8','windows-1252', $tel );
$mail = 'Adresse mail: ' . $mail;
$mail = iconv('UTF-8','windows-1252', $mail );
$lieudepart = 'Lieu de départ: ' . $lieudepart;
$lieudepart = iconv('UTF-8','windows-1252', $lieudepart );
$nomexcur = 'Type d\'éxcursion: ' . $nomexcur;
$nomexcur = iconv('UTF-8','windows-1252', $nomexcur );
$datea = 'Date de départ: ' . $datea;
$datea = iconv('UTF-8','windows-1252', $datea );
$dater = 'Date d\'arrivée: ' . $dater;
$dater = iconv('UTF-8','windows-1252', $dater );
$datei = 'Date d\'enregistrement: ' . $datei;
$datei = iconv('UTF-8','windows-1252', $datei );
$infocomp = 'Informations complementaires :';
$infocomp = iconv('UTF-8','windows-1252', $infocomp );
$msg1 = 'Nos équipes vous contacteront une semaine avant le début de l\'excursion afin de faire le point avec vous.';
$msg1 = iconv('UTF-8','windows-1252', $msg1 );
$msg2 = 'Une liste des équipements nécessaires vous sera transmise à ce moment.';
$msg2 = iconv('UTF-8','windows-1252', $msg2 );
$msg3 = 'Nous restons à votre disposition pour répondre à vos questions.';
$msg3 = iconv('UTF-8','windows-1252', $msg3 );
$contact = 'Contact par mail : info.excursionjes@gmail.com';
$contact = iconv('UTF-8','windows-1252', $contact );

$logo = 'Confirmations/images/logo.png';
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image($logo);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',20);
$pdf->Cell(40,10,$titre);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,$recap);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$pdf->Cell(40,10,$nom);
$pdf->Ln();
$pdf->Cell(40,10,$prenom);
$pdf->Ln();
$pdf->Cell(40,10,$tel);
$pdf->Ln();
$pdf->Cell(40,10,$mail);
$pdf->Ln();
$pdf->Cell(40,10,$lieudepart);
$pdf->Ln();
$pdf->Cell(40,10,$nomexcur);
$pdf->Ln();
$pdf->Cell(40,10,$datea);
$pdf->Ln();
$pdf->Cell(40,10,$dater);
$pdf->Ln();
$pdf->Cell(40,10,$datei);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(40,10,$infocomp);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(40,10,$msg1);
$pdf->Ln();
$pdf->Cell(40,10,$msg2);
$pdf->Ln();
$pdf->Cell(40,10,$msg3);
$pdf->Ln();
$pdf->Cell(40,10,$contact);
$pdf->Output('F', 'Confirmations/recapitulatif'.$idp.'_'.$ide.'.pdf');
echo "<hr/><a href='Confirmations/recapitulatif".$idp."_".$ide.".pdf' target='_blank'>Télécharger récapitulatif</a>";