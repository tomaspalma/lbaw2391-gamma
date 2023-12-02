<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DateController extends Controller
{
    public static function format_date($date): string
    {
        $dateTime = new \DateTime($date);
        $now = new \DateTime();
        $interval = $dateTime->diff($now);
        if ($interval->y > 0) {
            if ($interval->y == 1) {
                return $interval->y . " year ago";
            } else {
                return $interval->y . " years ago";
            }
        } elseif ($interval->m > 0) {
            if ($interval->m == 1) {
                return $interval->m . " month ago";
            } else {
                return $interval->m . " months ago";
            }
        } elseif ($interval->d > 0) {
            if ($interval->d == 1) {
                return $interval->d . " day ago";
            } else {
                return $interval->d . " days ago";
            }
        } elseif ($interval->h > 0) {
            if ($interval->h == 1) {
                return $interval->h . " hour ago";
            } else {
                return $interval->h . " hours ago";
            }
        } elseif ($interval->i > 0) {
            if ($interval->i == 1) {
                return $interval->i . " minute ago";
            } else {
                return $interval->i . " minutes ago";
            }
        } else {
            return "Just now";
        }
    }
}
