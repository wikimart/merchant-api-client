<?php

namespace Wikimart\MerchantAPIClient\Entities;

class PostPackageItem implements EntityInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = (string)$name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity( $quantity )
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return (int)$this->quantity;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return array(
            'name'     => $this->getName(),
            'quantity' => $this->getQuantity()
        );
    }
}