<?php

namespace Wkdcode\GarageModule\Api;

interface DoorRepositoryInterface
{
    /**
     * @return \Wkdcode\GarageModule\Api\Data\DoorInterface[]
     */
    public function getList();
}
