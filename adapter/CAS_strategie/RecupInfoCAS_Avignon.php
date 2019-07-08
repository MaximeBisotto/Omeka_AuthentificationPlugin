<?php

require_once "abstractRecupInfoCAS.php";

class RecupInfoCAS_Avignon extends abstractRecupInfoCAS
{

    public function getEmailData()
    {
        return $this->data["mail"];
    }
}