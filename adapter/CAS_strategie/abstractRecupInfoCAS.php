<?php


abstract class abstractRecupInfoCAS
{
    protected $data;

    public abstract function getEmailData();

    public abstract function getUsername();

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


}