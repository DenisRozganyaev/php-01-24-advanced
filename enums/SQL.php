<?php

namespace Enums;

enum SQL: string
{
    case IS = 'IS';
    case IS_NOT = 'IS NOT';
    case IN = 'IN';
    case NOT_IN = 'NOT IN';
    case NOT_EQUAL = '!=';
    case EQUAL = '=';
    case MORE = '>';
    case MORE_OR_EQUAL = '>=';
    case LESS = '<';
    case LESS_OR_EQUAL = '<=';
    case NULL = 'NULL';
}
