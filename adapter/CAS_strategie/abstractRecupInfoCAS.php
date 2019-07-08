<?php


abstract class abstractRecupInfoCAS
{
    protected $data;

    public abstract function getEmailData();

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


}