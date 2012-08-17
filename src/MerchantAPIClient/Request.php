<?php

namespace MerchantAPIClient;

class Request
{
    const FORMAT_XML = 'application/xml';
    const FORMAT_JSON = 'application/json';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /** @var int URI запроса */
    protected $uri;
    /** @var string Формат запроса и ответа */
    protected $format;
    /** @var string Данные запроса в виде строки //todo прикрутить сериалайзер в нужный формат */
    protected $data;
    /** @var string Метод запроса */
    protected $method;
    /** @var \DateTime Дата и время запроса */
    protected $date;

    public function __construct( $uri, $method = self::METHOD_GET, $format = self::FORMAT_JSON )
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->format = $format;
        $this->date = new \DateTime();
    }

    /**
     * @param string $data
     */
    public function setData( $data )
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $format
     */
    public function setFormat( $format )
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $method
     */
    public function setMethod( $method )
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param int $uri
     */
    public function setUri( $uri )
    {
        $this->uri = $uri;
    }

    /**
     * @return int
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate( \DateTime $date )
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDateAsString()
    {
        return $this->date->format( DATE_RFC2822 );
    }


}