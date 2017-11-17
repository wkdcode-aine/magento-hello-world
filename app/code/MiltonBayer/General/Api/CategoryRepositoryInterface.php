<?php

namespace MiltonBayer\General\Api;

interface CategoryRepositoryInterface
{
    /**
     * @return \MiltonBayer\General\Api\Data\CategoryInterface[]
     */
    public function getWordpressList();
}
