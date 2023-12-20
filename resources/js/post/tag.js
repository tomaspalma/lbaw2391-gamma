import Tagify from '@yaireo/tagify';
import { getUsername } from '../utils';

const postContent = document.getElementById("content");

// depending on the prefix pattern typed (@/#). See settings below.
// let friends = [
//     { value: 800, text: 'randy', title: 'Randy Marsh' },
//     { value: 900, text: 'Mr. Garrison', title: 'POTUS' },
//     { value: 1000, text: 'Mr. Mackey', title: "M'Kay" }
// ];
//



var whitelist_1 = [
    {
        "value": 300,
        "id": 2,
        "username": "alanturing",
        "email": "alanturing@example.com",
        "image": null,
        "academic_status": "Professor",
        "university": "University of Porto",
        "description": "A professor in Computer Science",
        "display_name": "Alan Turing",
        "is_private": false,
        "role": 2,
        "tsvectors": "'alan':2B 'alantur':1A 'ture':3B",
        "pivot": {
            "friend1": 1,
            "friend2": 2
        }
    },
    {
        "value": 400,
        "id": 2,
        "username": "admin",
        "email": "alanturing@example.com",
        "image": null,
        "academic_status": "Professor",
        "university": "University of Porto",
        "description": "A professor in Computer Science",
        "display_name": "Alan Turing",
        "is_private": false,
        "role": 2,
        "tsvectors": "'alan':2B 'alantur':1A 'ture':3B",
        "pivot": {
            "friend1": 1,
            "friend2": 2
        }
    },

]

let value = 0;
fetch(`/api/users/${getUsername()}/friends`).then(async (res) => {
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

    // A good place to pull server suggestion list accoring to the prefix/value
    tagify.on('input', function(e) {

    })

    tagify.on('add', function(e) {
        console.log("Friends: ", friends);
        console.log(e)
    })
}).catch((e) => console.error(e));

