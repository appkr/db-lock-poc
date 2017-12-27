<?php

namespace Myshop\Common\Model;

use MyCLabs\Enum\Enum;

/**
 * Class QueryOperator
 * @package Myshop\Common\Model
 *
 * @method static QueryOperator LIKE()
 * @method static QueryOperator NOT_LIKE()
 * @method static QueryOperator REGEXP()
 * @method static QueryOperator NOT_REGEXP()
 * @method static QueryOperator EQUAL()
 * @method static QueryOperator NOT_EQUAL()
 * @method static QueryOperator GT()
 * @method static QueryOperator GTE()
 * @method static QueryOperator LT()
 * @method static QueryOperator LTE()
 */
class QueryOperator extends Enum
{
    const LIKE = 'LIKE';
    const NOT_LIKE = 'NOT LIKE';
    const REGEXP = 'REGEXP';
    const NOT_REGEXP = 'NOT REGEXP';
    const EQUAL = '=';
    const NOT_EQUAL = '<>';
    const GT = '>';
    const GTE = '>=';
    const LT = '<';
    const LTE = '<=';
}