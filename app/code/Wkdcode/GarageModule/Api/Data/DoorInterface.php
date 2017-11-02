<?php

namespace Wkdcode\GarageModule\Api\Data;

interface DoorInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string|null
     */
    public function getType();

    /**
     * @return double|null
     */
    public function getPrice();
}
