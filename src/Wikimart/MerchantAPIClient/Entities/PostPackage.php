<?php

namespace Wikimart\MerchantAPIClient\Entities;

class PostPackage implements EntityInterface
{
    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $packageId;

    /**
     * @var PostPackageItem[]
     */
    protected $items = array();

    /**
     * @param \Wikimart\MerchantAPIClient\Entities\PostPackageItem[] $items
     * @throws \InvalidArgumentException
     */
    public function setItems( array $items )
    {
        foreach ( $items as $item ) {
            if ( !( $item instanceof PostPackageItem) ) {
                throw new \InvalidArgumentException( 'Elements of \'$items\' must be instance of PostPackageItem' );
            }
        }
        $this->items = $items;
    }

    /**
     * @return \Wikimart\MerchantAPIClient\Entities\PostPackageItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param PostPackageItem $item
     */
    public function addItem( PostPackageItem $item )
    {
        $this->items[] = $item;
    }

    /**
     * @param string $packageId
     */
    public function setPackageId($packageId)
    {
        $this->packageId = $packageId;
    }

    /**
     * @return string
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * @param string $service
     */
    public function setService( $service )
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array(
            'service'    => $this->getService(),
            'packageId'  => $this->getPackageId(),
            'items'      => array()
        );
        foreach( $this->getItems() as $item ) {
            $attributes['items'][] = $item->getAttributes();
        }
        return $attributes;
    }
}