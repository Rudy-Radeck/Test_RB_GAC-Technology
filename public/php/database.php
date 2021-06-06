<?php

class Database
{

    // Paramètres de la base de données
    private static $_db = "tickets_appels_bdd";
    private static $_dbHost="localhost";
    private static $_dbUser="User_GAC_test";
    private static $_dbPasswd="GAC_test2021";
    private static $_pdoAttributes=[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

    private static $_pdo;

    // Connection à la base de données
    public static function connect()
    {
            self::$_pdo = new PDO('mysql:host='.self::$_dbHost.';dbname='.self::$_db.'', self::$_dbUser, self::$_dbPasswd, self::$_pdoAttributes);
    }

    // Préparation et envoi d'un tableau (représentant un fichier csv) en base de données
    public static function InsertCsvArrayToDataBase(array $csv, $addHeaderLine = 0)
    {
        try {
            $pdo = self::$_pdo;
            $pdo->beginTransaction();
            // Modèle de la requête
            $stmt = $pdo->prepare("INSERT INTO `détail des appels bdd` (`compte_facture`, `num_facture`, `num_abonne`, `date`, `heure`, `durée`, `volume_réel`, `duree_facture`, `volume_facture`, `type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Variable pour stoquer l'index des lignes et les potentielles erreurs
            $line = 0 + $addHeaderLine;
            $errorListe = [];

            // Pour chaque entrée dans le tableau (une entrée représente une ligne du fichier csv )
            foreach ($csv as $row)
            {

                // Vérification des valeurs, rend null les champs vides, ou ajoute une chaîne de caractères vide au champ non nullable, récupère les potentiels erreurs.
                foreach ($row as $key => $value){
                    // Verification des valeurs, rend null les champs vides
                    if ($value == ''){
                        $row[$key] = null;
                    }
                    // Ajoute un champ vide au champ non nullable et récuperation des anomalies (champ vide censé être rempli)
                    if ($key == 'Compte facturé' && $value == null){
                        $row[$key] = '';
                        $errorListe[] = ["Potentielle erreur ligne: " . $line . " colonne: " . $key];
                    }
                    if ($key == 'N° Facture' && $value == null){
                        $row[$key] = '';
                        $errorListe[] = ["Potentielle erreur ligne: " . $line . " colonne: " . $key];
                    }
                    if ($key == 'N° abonné' && $value == null){
                        $row[$key] = '';
                        $errorListe[] = ["Potentielle erreur ligne: " . $line . " colonne: " . $key];
                    }
                    if ($key == 'Date' && $value == null){
                        $row[$key] = '';
                        $errorListe[] = ["Potentielle erreur ligne: " . $line . " colonne: " . $key];
                    }
                    if ($key == 'Heure' && $value == null){
                        $row[$key] = '';
                        $errorListe[] = ["Potentielle erreur ligne: " . $line . " colonne: " . $key];
                    }
                    if ($key == 'Type' && $value == null){
                        $row[$key] = '';
                        $errorListe[] = ["Potentielle erreur ligne: " . $line . " colonne: " . $key];
                    }
                }

                // Prépare les variables pour l'envoi
                $v1 = $row['Compte facturé'];
                $v2 = $row['N° Facture'];
                $v3 = $row['N° abonné'];

                //Modification des dates, de chaîne de caractères en format Date pour SQL
                $newFormat = DateTime::createFromFormat('d/m/Y', $row['Date']);
                $newFormat = $newFormat->format('Y-m-d');
                $v4 = $newFormat;


                $v5 = $row['Heure'];

                // Sépare les durées et les volumes dans des champs differents
                if (strpos($row['Durée/volume réel'], ":") === false){
                    //ligne basée sur un volume
                    $v6 = null;
                    $v7 = (float)$row['Durée/volume réel'];
                    $v8 = null;
                    $v9 = (float)$row['Durée/volume facturé'];

                }else{
                    //ligne basée sur une durée
                    $v6 = $row['Durée/volume réel'];
                    $v7 = null;
                    $v8 = $row['Durée/volume facturé'];
                    $v9 = null;
                }

                $v10 = $row['Type'];

                // Exécution de la requête
                $stmt->execute([$v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9, $v10]);

                $line++;
            }//fin du foreach

            // Valide les changements en base de données
            $pdo->commit();

        }catch (Exception $e){
            // roll back si problème
            $pdo->rollback();
            echo $e->getMessage();
        }
        // Retourne la liste de potentielles erreurs ou anomalies pour un retour visuel à l'utilisateur
        return $errorListe;
    }

    // Suprime les données de la Bdd et remet l'auto incrémentation de l'Id à 1
    public static function DeleteAllDataInDataBase(){
        $pdo = self::$_pdo;
        $stmt = $pdo->prepare("DELETE FROM `détail des appels bdd`");
        $stmt->execute();
        $stmt = $pdo->prepare("ALTER TABLE `détail des appels bdd` AUTO_INCREMENT=1");
        $stmt->execute();

    }

    // Retrouver la durée totale réelle des appels effectués après le 15/02/2012 (inclus)
    public static function CallAfterThisDate($date){
        $pdo = self::$_pdo;
        $stmt = $pdo->prepare("SELECT `durée` FROM `détail des appels bdd` WHERE `date` >= '$date' AND `durée` IS NOT NULL ");
        $stmt->execute();
        $selectedData = $stmt->fetchAll();

        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        // Recupération des données
        foreach ($selectedData as $value){
            $splite = explode(":", $value['durée']);
            $hours += $splite[0];
            $minutes += $splite[1];
            $seconds += $splite[2];
        }

        // Equilibrage des valeurs pour correspondre à des heures, minutes, secondes
        while ($seconds >= 60)
        {$minutes = $minutes + 1; $seconds = $seconds - 60;}
        while ($minutes >= 60)
        {$hours = $hours + 1; $minutes = $minutes - 60;}

        //retourne le résultat dans un tableau
        return $result = ["hours" => $hours, "minutes" => $minutes, "seconds" => $seconds];
    }


    // Retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00 pour l'ensemble.
    public static function DataBilledBetweenTwoHours(){
        $pdo = self::$_pdo;
        $stmt = $pdo->prepare("SELECT * FROM `détail des appels bdd` WHERE `heure` BETWEEN '00:00:00' AND '08:00:00' OR `heure` BETWEEN '18:00:00' AND '23:59:59' ORDER BY `volume_facture` DESC LIMIT 10");
        $stmt->execute();
        $selectedData = $stmt->fetchAll();
        return $selectedData;
    }

    // Retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00, par abonné.
    public static function DataBilledBetweenTwoHoursBySubscriber($numSubscriber){
        $pdo = self::$_pdo;
        $stmt = $pdo->prepare("SELECT * FROM `détail des appels bdd` WHERE `num_abonne` = '$numSubscriber' AND `heure` BETWEEN '00:00:00' AND '08:00:00' OR `num_abonne` = '$numSubscriber' AND `heure` BETWEEN '18:00:00' AND '23:59:59' ORDER BY `volume_facture` DESC LIMIT 10");
        $stmt->execute();
        $selectedData = $stmt->fetchAll();
        return $selectedData ;
    }


    // Retrouver la quantité totale de SMS envoyés par l'ensemble des abonnés
    public static function TotalSms(){
        $pdo = self::$_pdo;
        $stmt = $pdo->prepare("SELECT count(*) FROM `détail des appels bdd` WHERE `type` LIKE '%sms%'");
        $stmt->execute();
        $selectedData = $stmt->fetch();
        return $selectedData['count(*)'];
    }

}