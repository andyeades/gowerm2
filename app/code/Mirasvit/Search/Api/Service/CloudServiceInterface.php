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
 * @package   mirasvit/module-search
 * @version   1.0.150
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\Search\Api\Service;

interface CloudServiceInterface
{
    /**
     * @param string $module
     * @param string $entity
     * @return array
     */
    public function getList($module, $entity);

    /**
     * @param string $module
     * @param string $entity
     * @param string $identifier
     * @return string
     */
    public function get($module, $entity, $identifier);
}