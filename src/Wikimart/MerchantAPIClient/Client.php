<?php

namespace Wikimart\MerchantAPIClient;

class Client
{
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const STATUS_OPENED    = 'opened';
    const STATUS_CANCELED  = 'canceled';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ANNULED   = 'annuled';
    const STATUS_INVALID   = 'invalid';
    const STATUS_FAKED     = 'faked';

    /**
     * @var string Хост
     */
    protected $host;

    /**
     * @var string Идентификатор доступа
     */
    protected $accessId;

    /**
     * @var string Секретный ключ
     */
    protected $secretKey;

    /**
     * @param $host      Хост Wikimart merchant API
     * @param $appID     Идентификатор доступа
     * @param $appSecret Секретный ключ
     */
    public function __construct( $host, $appID, $appSecret )
    {
        $this->host      = $host;
        $this->accessId  = $appID;
        $this->secretKey = $appSecret;
    }

    /**
     * Возвращает хост Wikimart merchant API
     *
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

    /**
     * @param string $URI
     * @param string $method Метод HTTP запроса. Может принимать значения: 'GET', 'POST', 'PUT', 'DELETE'.
     * @param string $body
     *
     * @return \Wikimart\MerchantAPIClient\Response
     * @throws MerchantAPIException
     * @throws \InvalidArgumentException
     */
    public function api( $URI, $method, $body = null )
    {
        if ( !is_string( $URI ) ) {
            throw new \InvalidArgumentException( 'Argument \'$URI\' must be string' );
        }

        if ( !is_string( $method ) ) {
            throw new \InvalidArgumentException( 'Argument \'$method\' must be string' );
        }

        $validMethod = array( self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE );
        if ( !in_array( $method, $validMethod ) ) {
            throw new \InvalidArgumentException( 'Valid values for argument \'$method\' is: ' . implode( ', ', $validMethod ) );
        }

        if ( $body !== null && !is_string( $body ) ) {
            throw new \InvalidArgumentException( 'Argument \'$body\' must be string' );
        }

        $date = new \DateTime();

        $curl = curl_init( $this->host . $URI );
        $headers = array(
            'Accept: application/json',
            'X-WM-Date: ' . $date->format( DATE_RFC2822 ),
            'X-WM-Authentication: ' . $this->getAccessId() . ':' . $this->generateSignature( $URI, $method, $body, $date )
        );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        switch ( $method ) {
            case self::METHOD_GET:
                break;
            case self::METHOD_POST:
                $headers[] = 'Content-Type: application/json';
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
                curl_setopt( $curl, CURLOPT_POSTFIELDS, $body );
                break;
            case self::METHOD_PUT:
                $headers[] = 'Content-Type: application/json';
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
                curl_setopt( $curl, CURLOPT_POSTFIELDS, $body );
                break;
            case self::METHOD_DELETE:
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );
                break;
        }
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
        $httpResponse = curl_exec( $curl );
        if ( $httpResponse === false ) {
            throw new MerchantAPIException( 'Can`t get response' );
        }
        $httpCode     = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        curl_close( $curl );

        $data = $httpResponse;
        if ( $decoded = json_decode( $httpResponse ) ) {
            $data = $decoded;
        }

        $error = null;
        if ( $httpCode !== 200 ) {
            if ( is_object( $data ) && property_exists( $data, 'message' ) ) {
                $error = $data->message;
            }
        }
        $response = new Response( $data, $httpCode, $error );
        return $response;
    }

    /**
     * @param string    $URI
     * @param string    $method
     * @param string    $body
     * @param \DateTime $date
     *
     * @return string
     */
    protected function generateSignature( $URI, $method, $body, \DateTime $date )
    {
        $stringToHash = $method . "\n"
            . md5( $body ) . "\n"
            . $date->format( DATE_RFC2822 ) . "\n"
            . $URI;
        return hash_hmac( 'sha1', $stringToHash, $this->getSecretKey() );
    }

#                                           A P I      M E T H O D S
#                                          ==========================

    /**
     * Получение информации о заказе
     *
     * @param integer $orderID Идентификатор заказа
     *
     * @return \Wikimart\MerchantAPIClient\Response
     * @throws \InvalidArgumentException
     */
    public function methodGetOrder( $orderID )
    {
        if ( !is_integer( $orderID ) ) {
            throw new \InvalidArgumentException( 'Argument \'$orderID\' must be integer' );
        }
        return $this->api( '/api/1.0/orders/' . $orderID, self::METHOD_GET );
    }

    /**
     * Получение списка заказов
     *
     * @param integer          $count              Колличество возвращаемых заказов
     * @param integer          $offset             Смещение
     * @param null|string      $status             Фильтр по статусам. Может принимать значения: opened (Новые),
     *                                             canceled (Отменённые), rejected (Не принятые), confirmed (Принятые),
     *                                             annuled (Аннулированные), invalid (Ошибки Викимарта), faked (Фейковые)
     * @param null|\DateTime   $transitionDateFrom Начало диапазона времени изменения статуса заказа
     * @param null|\DateTime   $transitionDateTo   Конец диапозона времени изменения статуса заказа
     * @param null|string      $transitionStatus
     *
     * @return \Wikimart\MerchantAPIClient\Response
     * @throws \InvalidArgumentException
     */
    public function methodGetOrderList( $count, $offset, $status = null, \DateTime $transitionDateFrom = null, \DateTime $transitionDateTo = null, $transitionStatus = null )
    {
        $params = array();

        if ( !is_integer( $count ) ) {
            throw new \InvalidArgumentException( 'Argument \'$count\' must be integer' );
        } else {
            $params['pageSize'] = $count;
        }

        if ( !is_integer( $offset ) ) {
            throw new \InvalidArgumentException( 'Argument \'$offset\' must be integer' );
        } else {
            $params['offset'] = $offset;
        }

        $validStatuses = array( 'opened', 'canceled', 'rejected', 'confirmed', 'annuled', 'invalid', 'faked' );

        if ( !is_null( $status ) ) {
            if ( !in_array( $status, $validStatuses ) ) {
                throw new \InvalidArgumentException( 'Valid values for argument \'$status\' is: ' . implode( ', ', $validStatuses ) );
            } else {
                $params['status'] = $status;
            }
        }

        if ( !is_null( $transitionDateTo ) ) {
            $params['transitionDateFrom'] = $transitionDateFrom->format( DATE_RFC2822 );
        }

        if ( !is_null( $transitionDateTo ) ) {
            $params['transitionDateTo'] = $transitionDateTo->format( DATE_RFC2822 );
        }

        if ( !is_null( $transitionStatus ) ) {
            if ( !in_array( $status, $transitionStatus ) ) {
                throw new \InvalidArgumentException( 'Valid values for argument \'$transitionStatus\' is: ' . implode( ', ', $validStatuses ) );
            } else {
                $params['transitionStatus'] = $transitionStatus;
            }
        }

        return $this->api( '/api/1.0/orders?' . http_build_query( $params ), self::METHOD_GET );
    }

    /**
     * Получение списка причин для смены статуса
     *
     * @param $orderID Идентификатор заказа
     *
     * @return \Wikimart\MerchantAPIClient\Response
     * @throws \InvalidArgumentException
     */
    public function methodGetOrderStatusReasons( $orderID )
    {
        if ( !is_integer( $orderID ) ) {
            throw new \InvalidArgumentException( 'Argument \'$orderID\' must be integer' );
        }
        return $this->api( "/api/1.0/orders/$orderID/transitions", self::METHOD_GET );
    }

    public function methodSetOrderStatus( $orderID, $status, $reasonID, $comment )
    {
        if ( !is_integer( $orderID ) ) {
            throw new \InvalidArgumentException( 'Argument \'$orderID\' must be integer' );
        }

        $validStatuses = array( 'opened', 'canceled', 'rejected', 'confirmed', 'annuled', 'invalid', 'faked' );
        if ( !in_array( $status, $validStatuses ) ) {
            throw new \InvalidArgumentException( 'Valid values for argument \'$status\' is: ' . implode( ', ', $validStatuses ) );
        }

        if ( !is_integer( $reasonID ) ) {
            throw new \InvalidArgumentException( 'Argument \'$reasonID\' must be integer' );
        }

        if ( !is_string( $comment ) ) {
            throw new \InvalidArgumentException( 'Argument \'$comment\' must be string' );
        }

        $putBody = array(
            'request' => array(
                'status' => $status,
                'reasonId' => $reasonID,
                'comment' => $comment,
            )
        );
        return $this->api( "/api/1.0/orders/$orderID/status", self::METHOD_PUT, json_encode( $putBody ) );
    }

    /**
     * Получение истории смены статусов заказа
     *
     * @param $orderID
     *
     * @return Response
     * @throws \InvalidArgumentException
     */
    public function methodGetOrderStatusHistory( $orderID )
    {
        if ( !is_integer( $orderID ) ) {
            throw new \InvalidArgumentException( 'Argument \'$orderID\' must be integer' );
        }
        return $this->api( "/api/1.0/orders/$orderID/statuses", self::METHOD_GET );
    }
}