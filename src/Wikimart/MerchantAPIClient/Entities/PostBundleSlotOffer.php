<?php

namespace Wikimart\MerchantAPIClient\Entities;

class PostBundleSlotOffer implements EntityInterface
{
    /** @var string */
    protected $ownId;
    /** @var int */
    protected $ymlId;

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array(
            'ownId' => $this->getOwnId()
        );
        if ( !is_null( $this->getYmlId() ) ) {
            $attributes['ymlId'] = $this->getYmlId();
        }
        return $attributes;
    }

    /**
     * @param string $ownId
     */
    public function setOwnId( $ownId )
    {
        $this->ownId = (string) $ownId;
    }

    /**
     * @return string
     */
    public function getOwnId()
    {
        return $this->ownId;
    }

    /**
     * @param int $ymlId
     */
    public function setYmlId( $ymlId )
    {
        $this->ymlId = (int) $ymlId;
    }

    /**
     * @return int
     */
    public function getYmlId()
    {
        return $this->ymlId;
    }

}