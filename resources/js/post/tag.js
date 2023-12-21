import Tagify from '@yaireo/tagify';

import { getUsername } from '../utils';

const postContent = document.getElementById("content");

let value = 0;
fetch(`/api/users/${getUsername()}/friends/json`).then(async (res) => {
    const friends = await res.json();

    for (let friend of friends) {
        friend.value = value;
        value += 1;
    }

    const tagify = new Tagify(postContent, {
        //  mixTagsInterpolator: ["{{", "}}"],
        mode: 'mix',  // <--  Enable mixed-content
        pattern: /@/,  // <--  Text starting with @ or # (if single, String can be used here)
        enforceWhitelist: true,
        tagTextProp: 'username',  // <-- the default property (from whitelist item) for the text to be rendered in a tag element.
        // Array for initial interpolation, which allows only these tags to be used
        whitelist: friends.map(function(item) {
            return typeof item == 'string' ? { value: item } : item
        }),
        dropdown: {
            enabled: 1,
            position: 'text', // <-- render the suggestions list next to the typed text ("caret")
            mapValueTo: 'username', // <-- similar to above "tagTextProp" setting, but for the dropdown items
            highlightFirst: true  // automatically highlights first sugegstion item in the dropdown
        },
        callbacks: {
            add: console.log,  // callback when adding a tag
            remove: console.log   // callback when removing a tag
        }
    })

    tagify.on('add', function(e) {
        console.log(e)
    })
}).catch((e) => console.error(e));

