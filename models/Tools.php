<?php

class Tools
{
    /*
     * @param $email mail of the user
     * @return route for redirect to the CAS or NULL if don't use CAS
     */
    public static function externalEmail($email) {
        require dirname(__FILE__) . '/../config/route.php';
        $nomDomaine = explode('@', $email); //for use the part after the '@'
        $nomDomaine = explode('.', $nomDomaine[1]);
        $domaine = $nomDomaine[sizeof($nomDomaine) - 2] . "." . $nomDomaine[sizeof($nomDomaine) - 1]; // for don't use the subdomain
        foreach ($route as $item) {
            if ($item['domaine'] == $domaine) {
                return '/' . $item['route'];
            }
        }
        return null;
    }

    /*
     * @param $email mail of the user
     * @return boolean if the user or a student or not
     */
    public static function isStudentMail($email) {
        require dirname(__FILE__) . '/../config/route.php';
        $nomDomaine = explode('@', $email);
        $domaine = $nomDomaine[sizeof($nomDomaine) - 1]; //for use the part after the '@'
        foreach ($route as $item) {
            if ($item['etudiant'] == $domaine) {
                return true;
            }
        }
        return false;
    }

    /*
     * @param $email mail of the user
     * @return label use when I redirectthe user to the CAS
     */
    public static function mailToLabel($email) {
        require dirname(__FILE__) . '/../config/route.php';
        $nomDomaine = explode('@', $email); //for use the part after the '@'
        $nomDomaine = explode('.', $nomDomaine[1]);
        $domaine = $nomDomaine[sizeof($nomDomaine) - 2] . "." . $nomDomaine[sizeof($nomDomaine) - 1]; // for don't use the subdomain
        foreach ($route as $item) {
            if ($item['domaine'] == $domaine) {
                return $item['label'];
            }
        }
        return null;
    }
}
