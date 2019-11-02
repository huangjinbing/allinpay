<?php

namespace TongLian\Allinpay\Requests;


class MerchantRequest
{
    private $date;
    private $fileType;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param mixed $fileType
     */
    public function setFileType($fileType): void
    {
        $this->fileType = $fileType;
    }



}