<?php


abstract class abstractRecupInfoCAS
{
    protected $data;

    /*
     * return the email send by the CAS
     */
    public abstract function getEmailData();

    /*
     * return the first and the last name send by the CAS
     */
    public abstract function getUsername();

    /**
     * Set data receive to the CAS
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


}