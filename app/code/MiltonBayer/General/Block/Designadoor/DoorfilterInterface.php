<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MiltonBayer\General\Block\Designadoor;

/**
 * Interface DoorfilterInterface
 * @api
 * @since 100.0.2
 */
interface DoorfilterInterface
{
    /**
     * Render filter
     *
     * @param int $category_id
     * @return string
     */
    public function renderOptions(int $category_id);
}
