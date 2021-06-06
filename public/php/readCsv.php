<?php

class ReadCsv
{
    //
    const exclusionMotif = "/SELECT|INSERT|UPDATE|DELETE|CREATE|ALTER|INDEX|DROP|EXECUTE|TRIGGER|SHOW/i";

    /**
     * Méthode pour transformer un fichier csv en un tableau
     * @param $csvPath string Chemin du fichier
     * @param $headerSliceLength integer Nombre de lignes au début inutiles à supprimer (laisser les en-têtes des colonnes pour le mapping)
     * @return array
     */
    public static function CsvToArray($csvPath, $headerSliceLength)
    {
        // Vérification de l'existence du fichier sur le chemin indiqué
        if(!self::PathVerification($csvPath)){
            die('Erreur, fichier non trouver ou incorecte');
        }

        // Préparation du csv pour le mapping, suppression des lignes d'en-tête inutile
        $file = self::CsvPrepare($csvPath, $headerSliceLength);

        // Créer un tableau avec le fichier csv en mappant le fichier avec la fonction str_getcsv, encodée en utf8
        $rows   = array_map(function($data) {return str_getcsv(utf8_encode($data),";");} , $file);


        // Sécurisation des données avec htmlspecialchars et exclusion de motifs malveillants
        foreach ($rows as $Xkey => $row) {
            foreach ($row as $Ykey => $value){
                if(preg_match(self::exclusionMotif, $value)){
                    $value = '';
                }
                $rows[$Xkey][$Ykey] = htmlspecialchars(trim($value));
            }
        }


        // Récupération des en-têtes pour créer les clés du tableau
        $header = array_shift($rows);

        $csv    = [];
        // Pour chaque ligne on associe l'en-tête voulu avec sa valeur
        foreach($rows as $row) {
            $csv[] = array_combine($header, $row);
        }

        //Vérification du format du csv
        if ($header[0] != "Compte facturé"
            || $header[1] != "N° Facture"
            || $header[2] != "N° abonné"
            || $header[3] != "Date"
            || $header[4] != "Heure"
            || $header[5] != "Durée/volume réel"
            || $header[6] != "Durée/volume facturé"
            || $header[7] != "Type")
        {
            //format invalide
            $csv = false;
        }
        return $csv;
    }


    /**
     * Méthode de vérification de l'existence du fichier sur le chemin indiqué
     * @param $csvPath string Chemin du fichier
     * @return bool
     */
    private static function PathVerification($csvPath)
    {
        file_exists($csvPath)? $fileExiste = true : $fileExiste = false;
        return $fileExiste;
    }


    /**
     * Méthode de préparation du csv pour le mapping, suppression des lignes d'en-tête inutile
     * @param $csvPath string Chemin du fichier
     * @param $headerSliceLength integer Nombre de lignes au début inutiles à supprimer (laisser les en-têtes des colonnes pour le mapping)
     * @return array|false
     */
    private static function CsvPrepare($csvPath, $headerSliceLength)
    {
        $file = file($csvPath);
        array_splice($file, 0, $headerSliceLength);

        return $file;
    }




}