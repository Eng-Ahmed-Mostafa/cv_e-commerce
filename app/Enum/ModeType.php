<?php

namespace App\Enum;

enum ModeType : string
{
    case CREATE = 'create';
    case EDIT = 'edit';
    case DISPLAY = 'display';
}
