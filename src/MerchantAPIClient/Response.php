<?php

namespace MerchantAPIClient;

class Response
{
    protected $data;
    protected $status;
    protected $code;
    protected $error;

    public function setData( $data )
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setError( $error )
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setStatus( $status )
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCode( $code )
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }


}