<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

use Illuminate\View\View;

class GroupController extends Controller
{   
    public function showGroupForm(string $id): View
    {   
        $group = Group::findOrFail($id);
        $posts = $group->posts();
        $members = $group->users();
        return view('pages.group', ['feed' => 'posts',
        'posts' => $posts,
        'group' => $group,
        'members' => $members]);
    }

    public function showGroupMembers(string $id): View{

        $group = Group::findOrFail($id);
        $members = $group->users();
        $posts = $group->posts();
        return view('pages.group', ['feed' => 'members', 
        'members' => $members,
        'posts' => $posts,
        'group' => $group]);
    }
}
