<?php

namespace Wikimart\MerchantAPIClient\Entities;

class PostBundleSlot implements EntityInterface
{
    /** @var boolean */
    protected $isAnchor;
    /** @var string */
    protected $bonusType;
    /** @var float */
    protected $bonusAmount;
    /** @var PostBundleSlotOffer[] */
    protected $offers = array();

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array(
            'isAnchor' => $this->getIsAnchor()
        );
        if ( !is_null( $this->getBonusType() ) && !is_null( $this->getBonusAmount() ) ) {
            $attributes['bonusType']   = $this->getBonusType();
            $attributes['bonusAmount'] = $this->getBonusAmount();
        }
        foreach ( $this->getOffers() as $offer ) {
            $attributes['offers'][] = $offer->getAttributes();
        }
        return $attributes;
    }

    /**
     * @param PostBundleSlotOffer $offer
     */
    public function addOffer( PostBundleSlotOffer $offer )
    {
        $this->offers[] = $offer;
    }

    /**
     * @param float $bonusAmount
     */
    public function setBonusAmount( $bonusAmount )
    {
        $this->bonusAmount = $bonusAmount;
    }

    /**
     * @return float
     */
    public function getBonusAmount()
    {
        return $this->bonusAmount;
    }

    /**
     * @param string $bonusType
     */
    public function setBonusType( $bonusType )
    {
        $this->bonusType = $bonusType;
    }

    /**
     * @return string
     */
    public function getBonusType()
    {
        return $this->bonusType;
    }

    /**
     * @param boolean $isAnchor
     */
    public function setIsAnchor( $isAnchor )
    {
        $this->isAnchor = $isAnchor;
    }

    /**
     * @return boolean
     */
    public function getIsAnchor()
    {
        return $this->isAnchor;
    }

    /**
     * @param \Wikimart\MerchantAPIClient\Entities\PostBundleSlotOffer[] $offers
     *
     * @throws \InvalidArgumentException
     */
    public function setOffers( $offers )
    {
        foreach ( $offers as $offer ) {
            if ( !( $offer instanceof PostBundleSlotOffer) ) {
                throw new \InvalidArgumentException( 'Elements of \'$offers\' must be instance of PostBundleSlotOffer' );
            }
        }
        $this->offers = $offers;
    }

    /**
     * @return \Wikimart\MerchantAPIClient\Entities\PostBundleSlotOffer[]
     */
    public function getOffers()
    {
        return $this->offers;
    }

}