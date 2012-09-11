<?php

namespace Wikimart\MerchantAPIClient;

class Response
{
    protected $data;

    protected $httpCode;

    protected $error;

    public function __construct( $data, $httpCode, $error )
    {
        $this->data = $data;
        $this->httpCode = $httpCode;
        $this->error = $error;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}