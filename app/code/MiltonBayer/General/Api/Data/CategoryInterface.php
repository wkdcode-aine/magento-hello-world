<?php

namespace MiltonBayer\General\Api\Data;

interface CategoryInterface
{

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * Get category level
     *
     * @return int|null
     */
    public function getLevel();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getImageUrl();
}
