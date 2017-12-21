<?php

namespace Myshop\Common\Model;

use MyCLabs\Enum\Enum;

/**
 * Class ProductSortKey
 * @package Myshop\Common\Model
 *
 * @method static ProductSortKey CREATED_AT()
 * @method static ProductSortKey PRICE()
 * @method static ProductSortKey STOCK()
 */
class ProductSortKey extends Enum
{
    const CREATED_AT = 'CREATED_AT';
    const PRICE = 'PRICE';
    const STOCK = 'STOCK';
}