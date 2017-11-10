<?php

namespace Wkdcode\GarageModule\Api;

interface CategoryRepositoryInterface
{
    /**
     * @return \Wkdcode\GarageModule\Api\Data\CategoryInterface[]
     */
    public function getWordpressList();
}
