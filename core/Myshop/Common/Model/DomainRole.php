<?php

namespace Myshop\Common\Model;

use MyCLabs\Enum\Enum;

/**
 * Class DomainRole
 * @package Myshop\Common\Model
 *
 * @method static DomainRole ADMIN()
 * @method static DomainRole MEMBER()
 * @method static DomainRole USER()
 */
class DomainRole extends Enum
{
    const ADMIN = 'ADMIN';
    const MEMBER = 'MEMBER';
    const USER = 'USER';
}