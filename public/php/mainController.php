<?php
require_once (__DIR__ . DIRECTORY_SEPARATOR . "dataBase.php");
require_once (__DIR__ . DIRECTORY_SEPARATOR . "readCsv.php");

//__________*** Variables & préparation ***__________

// Nom du fichier csv, son chemin, et le nombre de lignes inutiles à supprimer au début de fichier (laisser les en-têtes des colonnes pour le mapping)
$csvName = 'tickets_appels_201202.csv';
$csvPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $csvName;
$headerRowsSlice = 2;

// Connection à la base de données
Database::connect();


//__________*** Gestion des demandes ***__________

// Préparation et insertion du fichier csv en base de données
if (isset($_POST['ajaxPost']) && $_POST['ajaxPost']== 'Insert'){
    if (Database::IsEmptyDatabase() <= 0)
    {
        $csv = ReadCsv::CsvToArray($csvPath,$headerRowsSlice);

        if ($csv != false)
        {
            echo json_encode(Database::InsertCsvArrayToDataBase($csv, $headerRowsSlice));
        }
        else
        {
            echo json_encode(false);
        }
    }
    else
    {
        echo json_encode(false);
    }
}
// Détermine si la base de données est vide ou si des données sont déjà présentes
if (isset($_POST['ajaxPost']) && $_POST['ajaxPost']== 'CheckDatabase'){
    echo json_encode(Database::IsEmptyDatabase());
}

// Suppression des informations en base de données
if (isset($_POST['ajaxPost']) && $_POST['ajaxPost']== 'Delete'){
    Database::DeleteAllDataInDataBase();
}

// Retrouver la durée totale réelle des appels effectués après le 15/02/2012 (inclus)
if (isset($_POST['ajaxPost']) && $_POST['ajaxPost']== 'CallAfterDate'){
    echo json_encode(Database::CallAfterThisDate("2012-02-15"));
}

// Retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00
if (isset($_POST['ajaxPost']) && $_POST['ajaxPost']== 'DataBilledBetweenTwoHours'){
    echo json_encode(Database::DataBilledBetweenTwoHours());
}

// Retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00, ==> par abonné <==.
if (isset($_POST['ajaxPost']) && $_POST['ajaxPost']== 'DataBilledBetweenTwoHoursBySubscribe'){
    if (isset($_POST['subscribe']) && $_POST['subscribe']!= null){
        $subscribe = $_POST['subscribe'];
        $subscribe = htmlspecialchars(trim($subscribe));
        $exclusionMotif = ReadCsv::exclusionMotif;
        if(preg_match($exclusionMotif, $subscribe)){
            $subscribe = '';
            die('');
        }
        echo json_encode(Database::DataBilledBetweenTwoHoursBySubscriber($subscribe));
    }else{
        echo json_encode('');
    }

}

// Retrouver la quantité totale de SMS envoyés par l'ensemble des abonnés
if (isset($_POST['ajaxPost']) && $_POST['ajaxPost']== 'TotalSms'){
    echo json_encode(Database::TotalSms());
}


