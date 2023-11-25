<?php

namespace App\Enums;

enum ReactionType: string
{
    case HEART = 'HEART';
    case LIKE = 'LIKE';
    case DISLIKE = 'DISLIKE';
    case STAR = 'STAR';
    
    /**
      * Gives the font awesome icon class equivalent for a certain reaction type
    */
    public function getViewIcon(): string 
    {
        return match ($this) {
            ReactionType::HEART => 'fa-heart',
            ReactionType::LIKE => 'fa-thumbs-up',
            ReactionType::DISLIKE => 'fa-thumbs-down',
            ReactionType::STAR => 'fa-star'
        };
    }

    public function getViewColor(): string 
    {
        return match($this) {
            ReactionType::HEART => 'heart-color',
            ReactionType::LIKE => 'like-color',
            ReactionType::DISLIKE => 'dislike-color',
            ReactionType::STAR => 'star-color'
        };
    }
};
