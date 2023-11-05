    INSERT INTO users (id, username, email, password, academic_status, display_name, is_private, role, image) VALUES 
        (1, 'johndoe', 'johndoe@example.com', 'password1', 'Undergraduate', 'John Doe', true, 2, 'https://upload.wikimedia.org/wikipedia/commons/a/af/Tux.png'),
        (2, 'alanturing', 'alanturing@example.com', 'password2', 'Professor', 'Alan Turing', false, 2, null),
        (3, 'adalovelace', 'adalovelace@example.com', 'password3', 'Graduate', 'Ada Lovelace', true, 2, null),
        (4, 'admin', 'admin@example.com', 'adminpassword', 'Administrator', 'Admin User', false, 1, null);

    INSERT INTO friend_request(user_id, friend_id, is_accepted, date) VALUES
        (2, 3, true, '1940-01-28 12:00:00'),
        (1, 4, true, '2023-05-17 15:30:00');

    INSERT INTO groups(id, name, description, is_private) VALUES 
        (1, 'Prolog Enthusiasts', 'A community for discussing Prolog programming language and related topics', false),
        (2, 'Tech Enthusiasts', 'A group dedicated to discussing the latest technology trends and innovations', true);

    INSERT INTO group_user (user_id, group_id) VALUES
        (1, 2),
        -- (1, 1),
        (2, 1),
        (2, 2);

    INSERT INTO group_owner (group_id, user_id) VALUES
        (1, 1),
        (2, 2);

    INSERT INTO post (id, author, title, content, attachment, group_id, is_private, date) VALUES
        (1, 1, 'Exciting AI Research Findings', 'Exciting new research findings in the field of artificial intelligence!', 'ai_research.pdf', 1, false, NOW() - INTERVAL '1 day'),
        (2, 2, 'Renewable Energy Discussion', 'Important discussion on renewable energy solutions for the future.', 'renewable_energy.png', 2, true, NOW() - INTERVAL '1 day'),
        (3, 3, 'Quantum Computing Paper Published', 'Just published my new research paper on quantum computing!', 'quantum_paper.pdf', null, false, NOW() - INTERVAL '3 days'),
        (4, 1, 'SpaceXs Mars Colonization Plans', 'Exciting news for all tech enthusiasts - SpaceX plans to colonize Mars!', null, null, true, NOW() - INTERVAL '4 days'),
        (5, 2, 'Exploring 6G Technology', 'Discussing the potential of 6G technology and its impact on communication.', null, 2, false, NOW() - INTERVAL '5 days'),
        (6, 1, 'History of Computer Science Lecture', 'Attended a fascinating lecture on the history of computer science today.', null, 1, false, NOW() - INTERVAL '6 days'),
        (7, 3, 'Challenges in Quantum Computing', 'Exploring the challenges and opportunities in the field of quantum computing.', null, 2, false, NOW() - INTERVAL '7 days'),
        (8, 4, 'Future of AI and Society', 'A sneak peek into the future of AI and its implications for society.', null, null, true, NOW() - INTERVAL '8 days'),
        (9, 2, 'Python 4.0 Announcement', 'Exciting times ahead for programmers with the launch of the new Python 4.0!', null, 1, false, NOW() - INTERVAL '9 days');


    INSERT INTO comment (id, post_id, author, content, date) VALUES
        (1, 1, 3, 'This is amazing! Can you share more details about the AI research findings?', NOW() - INTERVAL '1 day'),
        (2, 1, 4, 'Im eager to read the research paper. Please share the link when its available!', NOW() - INTERVAL '23 hours'),
        (3, 2, 1, 'Renewable energy is the future, and we need to invest more in it.', NOW() - INTERVAL '1 day'),
        (4, 4, 3, 'The smartphone industry is advancing rapidly. Any thoughts on sustainability?', NOW() - INTERVAL '2 days'),
        (5, 7, 2, 'AIs impact on society is a crucial discussion. Lets explore it further.', NOW() - INTERVAL '2 days'),
        (6, 8, 4, 'Python 4.0 sounds exciting! What are the new features in this version?', NOW() - INTERVAL '3 days'),
        (7, 5, 1, 'Id love to hear more about the history of computer science. Please share insights!', NOW() - INTERVAL '4 days'),
        (8, 3, 2, 'Congratulations on your new research paper on quantum computing!', NOW() - INTERVAL '4 days');

    INSERT INTO reaction (post_id, comment_id ,author, type) VALUES
        (1, NULL, 3, 'LIKE'),
        (NULL, 1, 2, 'HEART'),
        (1, NULL, 1, 'LIKE'),
        (3, NULL, 2, 'HEART'), 
        (NULL, 5, 4, 'STAR'),
        (6, NULL, 3, 'DISLIKE'),
    (NULL, 7, 2, 'DISLIKE');
