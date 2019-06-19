<?php

class Tools
{
    private static $domaineExterne = array("univ-avignon.fr" => "univ-avignon.fr");

    public static function internalEmail($email) {
        $nomDomaine = explode('.', $email);
        $domaine = $nomDomaine[sizeof($nomDomaine) - 2] . "." . $nomDomaine[sizeof($nomDomaine) - 1]; // supprime l'utilisateur et le sous-domaine si existant
        if (in_array($domaine, array_keys(Tools::$domaineExterne))) {
            return Tools::$domaineExterne;
        }
        return true;
    }
}
