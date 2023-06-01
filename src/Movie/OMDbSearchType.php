<?php

namespace App\Movie;

enum OMDbSearchType: string
{
    case ID = 'i';
    case TITLE = 't';
}
