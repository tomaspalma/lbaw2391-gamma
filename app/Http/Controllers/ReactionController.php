<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public static function reactionsMap($entity): array
    {
        $reactions = [];
        foreach ($entity->reactions as $reaction) {
            $icon = $reaction->type->getViewIcon();
            $color = $reaction->type->getViewColor();
            if (!isset($reactions[$icon])) {
                $reactions[$icon] = [1, $color, $reaction->type->value];
            } else {
                $reactions[$icon][0] += 1;
            }
        }

        return $reactions;
    }
}
