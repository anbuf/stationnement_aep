<?php

error_reporting(E_ALL);

require_once("class.authentification.php");
require_once("class.demandeListe.php");
require_once("class.demande.php");
require_once("class.log.php");
require_once("class.validation.php");

$objAuth = authentification::instance();
#$objLog  = log::instance();
#$objvalid= validation::instance();

if (getParam('logout') == '1')
{
    session_destroy();
    header("Location: auth.php");
    exit();
}

if (!$objAuth->estIdentifie())
{
    header("Location: auth.php");
    exit();
}

printn("<body onload=\"javascript:window.print()\">");


$objDemande = new demande();
$objDemande->ouvrir(getParam('id'));
printn("<a href=\"impressionChangementStatut.php?id=".getParam('id')."\">Changement statut pour \"Imprim�\".</a><br><br>");
printn("<img src=\"AEP.gif\"><br><br>");
printn("<table>");
printn("<tr><td>Ann�e scolaire</td><td>".$objDemande->getAnnee()."</td><tr>");
printn("<tr><td>ID</td><td>".$objDemande->getId()."</td></tr>");
printn("<tr><td>Statut</td><td>".printLecture('statusLong',$objDemande->getId())."</td></tr>");
printn("<tr><td>Pr�nom</td><td>".$objDemande->getPrenom()."</td></tr>");
printn("<tr><td>Nom</td><td>".$objDemande->getNom()."</td></tr>");
printn("<tr><td>Matricule</td><td>".$objDemande->getMatricule()."</td></tr>");
printn("<tr><td>Adresse</td><td>".$objDemande->getAdresse()."</td></tr>");
printn("<tr><td>Ville</td><td>".$objDemande->getVille()."</td></tr>");
printn("<tr><td>Code Postal</td><td>".$objDemande->getCodePostal()."</td></tr>");
printn("<tr><td>T�l�phone domicile&nbsp;&nbsp;</td><td>".$objDemande->getTelDomicile()."</td></tr>");
printn("<tr><td>T�l�phone bureau</td><td>".$objDemande->getTelBureau()."</td></tr>");
#printn("<tr><td>Statut �tudiant</td><td>".$objDemande->getStatusEtudiant()."</td></tr>");
printn("<tr><td>Email</td><td>".$objDemande->getEmail()."</td></tr>");
printn("<tr><td>Type paiement</td><td>".printLecture('paiement',$objDemande->getPaiement())."</td></tr>");
printn("<tr><td valign=top>Notes</td><td>".preg_replace("/\n/","<br>\n",$objDemande->getNote())."</td></tr>");
printn("</table><br><br>");
printn("<table>");
printn("<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Vehicule 1</td><td>&nbsp;&nbsp;&nbsp;</td><td>Vehicule 2</td></tr>");
printn("<tr><td>Couleur</td><td align=center>".$objDemande->getVehicule1Couleur()."</td><td></td><td align=center>".$objDemande->getVehicule2Couleur()."</td></tr>");
printn("<tr><td>Marque</td><td align=center>".$objDemande->getVehicule1Marque()."</td><td></td><td align=center>".$objDemande->getVehicule2Marque()."</td></tr>");
printn("<tr><td>Ann�e</td><td align=center>".$objDemande->getVehicule1Annee()."</td><td></td><td align=center>".$objDemande->getVehicule2Annee()."</td></tr>");
printn("<tr><td>Plaque</td><td align=center>".$objDemande->getVehicule1Plaque()."</td><td></td><td align=center>".$objDemande->getVehicule2Plaque()."</td></tr>");

printn("</table>");
printn("</body>");

exit(0);


function getParam($param)
{
    if (isset($_POST[$param]))
    {
        return $_POST[$param];
    }
    if (isset($_GET[$param])) 
    {
        return $_GET[$param];
    }
    return null;
}

function printn ($txt) { print $txt."\n"; }

// Imprime le bon texte en fonction de la valeu et du type
function printLecture($type, $valeur)
{
    if ($type == "groupe")
    {
        if ($valeur == DEMANDE_GROUPE_AEP)
        {
            return "AEP";
        }
    }
    elseif ($type == "status")
    {
        if ($valeur == DEMANDE_STATUS_ATTENTE)
        {
            return "Attente";
        }
        if ($valeur == DEMANDE_STATUS_REFUSE)
        {
            return "Refus";
        }
        if ($valeur == DEMANDE_STATUS_ACCEPTE)
        {
            return "Accept�";
        }
        if ($valeur == DEMANDE_STATUS_PAYE)
        {
            return "Pay�";
        }
        if ($valeur == DEMANDE_STATUS_PREUVEOK)
        {
            return "PreuvesOK";
        }
        if ($valeur == DEMANDE_STATUS_ANNULE)
        {
            return "Annul�";
        }
        if ($valeur == DEMANDE_STATUS_IMPRIME)
        {
            return "Imprim�";
        }
    }
    elseif ($type == "statusLong")
    {
        if ($valeur == DEMANDE_STATUS_ATTENTE)
        {
            return "Demande re�ue, en attente...  Pi�ces justificatives NON-RE�UES";
        }
        if ($valeur == DEMANDE_STATUS_REFUSE)
        {
            return "Demande refus�e";
        }
        if ($valeur == DEMANDE_STATUS_ACCEPTE)
        {
            return "Demande accept�e";
        }
        if ($valeur == DEMANDE_STATUS_PAYE)
        {
            return "Demande pay�e";
        }
        if ($valeur == DEMANDE_STATUS_PREUVEOK)
        {
            return "Demande re�ue, en attente...  Pi�ces justificatives RE�UES";
        }
        if ($valeur == DEMANDE_STATUS_ANNULE)
        {
            return "Demande annul�e";
        }
        if ($valeur == DEMANDE_STATUS_IMPRIME)
        {
            return "Demande accept�e et transf�r�e pour le SDI";
        }
    }
    elseif ($type == "paiement")
    {
        if ($valeur == DEMANDE_TYPE_COMPTANT)
        {
            return "Comptant";
        }
        if ($valeur == DEMANDE_TYPE_CHEQUE)
        {
            return "Ch�que";
        }
        if ($valeur == DEMANDE_TYPE_MANDAT)
        {
            return "Mandat";
        }
        if ($valeur == DEMANDE_TYPE_INTERAC)
        {
            return "Interac";
        }
        if ($valeur == DEMANDE_TYPE_VISA)
        {
            return "Visa";
        }
        if ($valeur == DEMANDE_TYPE_MC)
        {
            return "Mastercard";
        }
    }
}

// Remplace les double guillements par des simples pour faciliter le tout avec le HTML.
function checkGuillemets($texte)
{
    return preg_replace('/"/',"'",$texte);
}

?>
