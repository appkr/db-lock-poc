<?php

namespace Myshop\Common\Model;

use MyCLabs\Enum\Enum;

/**
 * Class DomainPermission
 * @package Myshop\Common\Model
 *
 * @method static DomainPermission MANAGE_USER()
 * @method static DomainPermission MANAGE_PRODUCT()
 * @method static DomainPermission MANAGE_REVIEW()
 */
class DomainPermission extends Enum
{
    const MANAGE_USER = 'MANAGE_USER';
    const MANAGE_PRODUCT = 'MANAGE_PRODUCT';
    const MANAGE_REVIEW = 'MANAGE_REVIEW';
}