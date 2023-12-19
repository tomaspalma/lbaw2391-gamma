<?php

namespace App\Enums;

enum ReactionType: string
{
    case HEART = 'HEART';
    case LIKE = 'LIKE';
    case DISLIKE = 'DISLIKE';
    case STAR = 'STAR';
    case HANDSHAKE = 'HANDSHAKE';
    case HANDPOINTUP = 'HANDPOINTUP';

    /**
     * Gives the font awesome icon class equivalent for a certain reaction type
     */
    public function getViewIcon(): string
    {
        return match ($this) {
            ReactionType::HEART => 'fa-heart',
            ReactionType::LIKE => 'fa-thumbs-up',
            ReactionType::DISLIKE => 'fa-thumbs-down',
            ReactionType::STAR => 'fa-star',
            ReactionType::HANDSHAKE => 'fa-handshake',
            ReactionType::HANDPOINTUP => 'fa-hand-point-up'
        };
    }

    public function getViewColor(): string
    {
        return match ($this) {
            ReactionType::HEART => 'heart-color',
            ReactionType::LIKE => 'like-color',
            ReactionType::DISLIKE => 'dislike-color',
            ReactionType::STAR => 'star-color',
            ReactionType::HANDSHAKE => 'handshake-color',
            ReactionType::HANDPOINTUP => 'handshake-color'
        };
    }

    public function getVerb(): string
    {
        return match ($this) {
            ReactionType::HEART => 'hearted',
            ReactionType::LIKE => 'liked',
            ReactionType::DISLIKE => 'disliked',
            ReactionType::STAR => 'starred',
            ReactionType::HANDSHAKE => 'handshaked',
            ReactionType::HANDPOINTUP => 'reacted'
        };
    }
};
