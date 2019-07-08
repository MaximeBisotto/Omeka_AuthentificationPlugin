<?php

class Tools
{
    private static $domaineExterne = array("univ-avignon.fr" => "/users/cas");

    public static function externalEmail($email) {
        $nomDomaine = explode('.', $email);
        $domaine = $nomDomaine[sizeof($nomDomaine) - 2] . "." . $nomDomaine[sizeof($nomDomaine) - 1]; // supprime l'utilisateur et le sous-domaine si existant
        if (in_array($domaine, array_keys(Tools::$domaineExterne))) {
            return Tools::$domaineExterne[$domaine];
        }
        return false;
    }
}
