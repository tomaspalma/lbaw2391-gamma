@for($i = 0; $i < $members->count(); $i++)
    @include('partials.user_card', ['user' => $members->get()[$i], 'adminView' => false, 'group' => true])
    @endfor
