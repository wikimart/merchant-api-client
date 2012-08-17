<?php

namespace MerchantAPIClient;

class Client
{
    /** @var string Хост */
    protected $host;
    /** @var string Идентификатор доступа */
    protected $accessId;
    /** @var string Секретный ключ */
    protected $secretKey;

    protected $httpMethodMapping = array(
       Request::METHOD_GET => \HttpRequest::METH_GET,
       Request::METHOD_POST => \HttpRequest::METH_POST,
       Request::METHOD_PUT => \HttpRequest::METH_PUT,
       Request::METHOD_DELETE => \HttpRequest::METH_DELETE,
    );

    public function __construct( $host, $accessId, $secretKey )
    {
        $this->host = $host;
        $this->accessId = $accessId;
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getAccessId()
    {
        return $this->accessId;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function send( Request $request )
    {
        $httpRequest = new \HttpRequest();
        $httpRequest->setUrl( 'http://' . $this->getHost() . $request->getUri() );
        $httpRequest->setMethod( $this->httpMethodMapping[$request->getMethod()] );
        $httpRequest->setBody( $request->getData() );
        $httpRequest->setContentType( $request->getFormat() );

        $httpRequest->setHeaders( array(
            'Accept' => $request->getFormat(),
            'X-WM-Date' => $request->getDateAsString(),
            'X-WM-Authentication' => $this->getAccessId() . ':' . $this->getSignature( $request )
        ));

        $httpMessage = $httpRequest->send();

        $response = new Response();
        $response->setStatus( $httpMessage->getResponseStatus() );
        $response->setCode( $httpMessage->getResponseCode() );
        $response->setData( $httpMessage->getBody() );

        return $response;
    }

    private function getSignature( Request $request )
    {
        $dataToHash = $request->getMethod() . "\n"
            . md5( $request->getData() ) . "\n"
// Content-Type при входящем запросе не указывается, так что пока исключим его из сигнатуры
//            . $request->getFormat() . "\n"
            . $request->getDateAsString() . "\n"
            . $request->getUri();

        return hash_hmac( 'sha1', $dataToHash, $this->getSecretKey() );
    }

}