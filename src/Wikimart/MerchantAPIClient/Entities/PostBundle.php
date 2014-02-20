<?php

namespace Wikimart\MerchantAPIClient\Entities;

class PostBundle implements EntityInterface
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $description;
    /** @var boolean */
    protected $isAvailable;
    /** @var string */
    protected $startTime;
    /** @var string */
    protected $endTime;
    /** @var string */
    protected $bonusType;
    /** @var float */
    protected $bonusAmount;
    /** @var PostBundleSlot[] */
    protected $slots = array();

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array(
            'name'        => $this->getName(),
            'description' => $this->getDescription()
        );
        if ( !is_null( $this->getIsAvailable() ) ) {
            $attributes['isAvailable'] = $this->getIsAvailable();
        }
        if ( !is_null( $this->getStartTime() ) ) {
            $attributes['startTime'] = $this->getStartTime();
        }
        if ( !is_null( $this->getEndTime() ) ) {
            $attributes['endTime'] = $this->getEndTime();
        }
        if ( !is_null( $this->getBonusType() ) && !is_null( $this->getBonusAmount() ) ) {
            $attributes['bonusType']   = $this->getBonusType();
            $attributes['bonusAmount'] = $this->getBonusAmount();
        }
        foreach ( $this->getSlots() as $slot ) {
            $attributes['slots'][] = $slot->getAttributes();
        }
        return $attributes;
    }

    /**
     * @param PostBundleSlot $slot
     */
    public function addSlot( PostBundleSlot $slot )
    {
        $this->slots[] = $slot;
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
     * @param string $description
     */
    public function setDescription( $description )
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $endTime
     */
    public function setEndTime( $endTime )
    {
        $this->endTime = $endTime;
    }

    /**
     * @return string
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param boolean $isAvailable
     */
    public function setIsAvailable( $isAvailable )
    {
        $this->isAvailable = $isAvailable;
    }

    /**
     * @return boolean
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Wikimart\MerchantAPIClient\Entities\PostBundleSlot[] $slots
     *
     * @throws \InvalidArgumentException
     */
    public function setSlots( $slots )
    {
        foreach ( $slots as $slot ) {
            if ( !( $slot instanceof PostBundleSlot) ) {
                throw new \InvalidArgumentException( 'Elements of \'$slots\' must be instance of PostBundleSlot' );
            }
        }
        $this->slots = $slots;
    }

    /**
     * @return \Wikimart\MerchantAPIClient\Entities\PostBundleSlot[]
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * @param string $startTime
     */
    public function setStartTime( $startTime )
    {
        $this->startTime = $startTime;
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

}