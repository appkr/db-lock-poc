<?php

namespace Myshop\Common\Model;

use MyCLabs\Enum\Enum;

/**
 * Class SortDirection
 * @package Myshop\Common\Model
 *
 * @method static SortDirection ASC()
 * @method static SortDirection DESC()
 */
class SortDirection extends Enum
{
    const ASC = 'ASC';
    const DESC = 'DESC';
}