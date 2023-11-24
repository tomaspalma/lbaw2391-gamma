<?php

namespace App\Enums;

enum ReactionType: string
{
    case HEART = 'HEART';
    case LIKE = 'LIKE';
    case DISLIKE = 'DISLIKE';
    case START = 'STAR';
};
