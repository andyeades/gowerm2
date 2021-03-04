<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Kb\Api\Service\Articlesections;

interface ArticlesectionsManagementInterface
{
    /**
     * @param \Mirasvit\Kb\Model\Articlesections $articlesections
     * @param int                        $currentStore
     * @return bool
     */
    public function isAvailableForStore($articlesections, $currentStore = 0);

    /**
     * @param \Mirasvit\Kb\Model\Articlesections $articlesections
     * @param array                      $categories
     * @return array
     */
    public function getAvailableStores($articlesections, $categories = []);
}
