<?php

class ReadCsv
{
    //
    const exclusionMotif = "/SELECT|INSERT|UPDATE|DELETE|CREATE|ALTER|INDEX|DROP|EXECUTE|TRIGGER|SHOW/i";

    /**
     * Méthode pour transformer un fichier csv en un tableau
     * Prend en paramètre le chemin du fichier, et le nombre de lignes au debut inutile à supprimer (laisser les en-têtes des colonnes pour le mapping)
     * @param $csvPath
     * @param $headerSliceLength
     * @return array
     *
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
        return $csv;
    }

    // Méthode de vérification de l'existence du fichier sur le chemin indiqué
    private static function PathVerification($csvPath)
    {
        file_exists($csvPath)? $fileExiste = true : $fileExiste = false;
        return $fileExiste;
    }

    // Méthode de préparation du csv pour le mapping, suppression des lignes d'en-tête inutile
    private static function CsvPrepare($csvPath, $headerSliceLength)
    {
        $file = file($csvPath);
        array_splice($file, 0, $headerSliceLength);

        return $file;
    }




}