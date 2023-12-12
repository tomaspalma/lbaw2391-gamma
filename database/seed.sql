CREATE SCHEMA IF not exists lbaw2391;

DROP TABLE IF exists users CASCADE;
DROP TABLE IF exists password_reset_tokens CASCADE;
DROP TABLE IF exists post CASCADE;
DROP TABLE IF exists groups CASCADE;
DROP TABLE IF exists group_owner CASCADE;
DROP TABLE IF exists group_request CASCADE;
DROP TABLE IF exists group_user CASCADE;
DROP TABLE IF exists friend_request CASCADE;
DROP TABLE IF exists comment CASCADE;
DROP TABLE IF exists reaction CASCADE;
DROP TABLE IF exists post_tag_not CASCADE;
DROP TABLE IF exists group_request_not CASCADE;
DROP TABLE IF exists friend_request_not CASCADE;
DROP TABLE IF exists post_tag CASCADE;
DROP TABLE IF exists comment_not CASCADE;
DROP TABLE IF exists reaction_not CASCADE;
DROP TABLE IF exists group_ban CASCADE;
DROP TABLE IF exists app_ban CASCADE;
DROP TABLE IF exists friends CASCADE;
DROP TABLE IF exists appeal CASCADE;

-----------------------------------------
-- Types
-----------------------------------------

DROP TYPE if exists reaction_types;
CREATE TYPE reaction_types AS ENUM ('LIKE', 'DISLIKE', 'HEART', 'STAR');

-----------------------------------------
-- Tables
-----------------------------------------


CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL CONSTRAINT unique_username UNIQUE,
    email TEXT NOT NULL CONSTRAINT unique_email UNIQUE,
    password TEXT NOT NULL,
    image TEXT,
    academic_status TEXT,
    display_name TEXT,
    is_private BOOLEAN DEFAULT true NOT NULL,
    role INTEGER NOT NULL,
    email_verified_at TIMESTAMP WITH TIME ZONE
);

CREATE TABLE password_reset_tokens (
    email TEXT PRIMARY KEY,
    token TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now() CHECK (created_at <= now())
);

CREATE TABLE groups (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT unique_group_name UNIQUE,
    image TEXT,
    banner TEXT,
    is_private BOOLEAN DEFAULT true NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    author INTEGER REFERENCES users(id),
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    attachment TEXT,
    group_id INTEGER REFERENCES groups(id),
    is_private BOOLEAN NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE friends (
    friend1 INTEGER REFERENCES users(id),
    friend2 INTEGER REFERENCES users(id),
    PRIMARY KEY (friend1, friend2)
);


CREATE TABLE group_owner(
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE,
    PRIMARY KEY (user_id, group_id)
);

CREATE TABLE group_request(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE,
    is_accepted BOOLEAN DEFAULT false NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE group_user(
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE,
    PRIMARY KEY (user_id, group_id)
);

CREATE TABLE friend_request(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    friend_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    is_accepted BOOLEAN DEFAULT null,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE comment(
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    author INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    content TEXT NOT NULL
);

CREATE TABLE reaction (
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    comment_id INTEGER REFERENCES comment(id) ON UPDATE CASCADE,
    author INTEGER REFERENCES users(id),
    type reaction_types NOT NULL,
    CONSTRAINT valid_post_and_comment_ck CHECK((post_id IS NULL and comment_id IS NOT NULL) or (post_id IS NOT NULL and comment_id IS NULL))
);

CREATE TABLE post_tag_not(
    id SERIAL PRIMARY KEY, 
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE post_tag(
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE
);

CREATE TABLE group_request_not(
    id SERIAL PRIMARY KEY, 
    group_request_id INTEGER REFERENCES group_request(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK(date <= now())
);

CREATE TABLE friend_request_not(
    id SERIAL PRIMARY KEY, 
    friend_request INTEGER REFERENCES friend_request(id) ON UPDATE CASCADE ON DELETE CASCADE,
    is_accepted BOOLEAN,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK(date <= now()),
    read BOOLEAN DEFAULT false
);

CREATE TABLE comment_not(
    id SERIAL PRIMARY KEY, 
    comment_id INTEGER REFERENCES comment(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK(date <= now()),
    read BOOLEAN DEFAULT false
);

CREATE TABLE reaction_not(
    id SERIAL PRIMARY KEY, 
    reaction_id INTEGER REFERENCES reaction(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK(date <= now()),
    read BOOLEAN DEFAULT false
);

CREATE TABLE appeal(
    id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now() CHECK(created_at <= now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT now() CHECK(updated_at <= now())
);

CREATE TABLE group_ban(
    id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    group_owner_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    banned_user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE,
    appeal INTEGER REFERENCES appeal(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK(date <= now())
);

CREATE TABLE app_ban(
    id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    admin_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    banned_user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    appeal INTEGER REFERENCES appeal(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK(date <= now())
);


CREATE INDEX user_index ON users USING hash(id);
CREATE INDEX post_comment ON comment USING btree(post_id);
CREATE INDEX author_post ON post USING btree(author);

-----------------------------------------
-- Full-text search
-----------------------------------------

-- Search users by username
ALTER TABLE users ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION update_users_search() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = setweight(to_tsvector('english', NEW.username), 'A') ||
            setweight(to_tsvector('english', NEW.display_name), 'B');
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.username <> OLD.username OR NEW.display_name <> OLD.display_name) THEN
        NEW.tsvectors = setweight(to_tsvector('english', NEW.username), 'A') ||
            setweight(to_tsvector('english', NEW.display_name), 'B');
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER update_users_search
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE update_users_search();

CREATE INDEX user_search_idx ON users USING GIN (tsvectors);

-----------------------------------------

-- Search posts
ALTER TABLE post ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION update_post_search() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = setweight(to_tsvector('english', NEW.title), 'A') 
            || setweight(to_tsvector('english', NEW.content), 'B');
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (OLD.title <> NEW.title OR NEW.content <> OLD.content) THEN
            NEW.tsvectors = setweight(to_tsvector('english', NEW.title), 'A') 
            || setweight(to_tsvector('english', NEW.content), 'B');
        END IF;
    END IF;
    RETURN NEW;
END$$
LANGUAGE plpgsql;

CREATE TRIGGER update_post_search
    BEFORE INSERT OR UPDATE ON post
    FOR EACH ROW
    EXECUTE PROCEDURE update_post_search();

CREATE INDEX post_search_idx ON post USING GIN(tsvectors);

-- Search groups
ALTER TABLE groups ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION update_groups_search() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B');
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B');
        END IF;
    END IF;
    RETURN NEW;
END$$
LANGUAGE plpgsql;

CREATE TRIGGER update_groups_search
    BEFORE INSERT OR UPDATE ON groups
    FOR EACH ROW
    EXECUTE PROCEDURE update_groups_search();

CREATE INDEX groups_search_idx ON groups USING GIN(tsvectors);

-----------------------------------------
-- Triggers
-----------------------------------------

-- (TRIGGER01) If a user is deleted, it will change all his activity to anonymous
-- CREATE OR REPLACE FUNCTION update_deleted_user() RETURNS TRIGGER AS 
-- $BODY$
-- BEGIN
--     DELETE FROM post_tag_not WHERE id = (
--         SELECT post_tag.id 
--         FROM post_tag_not JOIN post_tag ON post_tag_not.post_id = post_tag.id
--         JOIN users ON users.id = post_tag.user_id
--         WHERE users.id = OLD.id
--     );
--     DELETE FROM post_tag_not WHERE id = (
--         SELECT post_tag.id 
--         FROM post_tag_not JOIN post_tag ON post_tag_not.post_id = post_tag.id
--         JOIN users ON users.id = post_tag.user_id
--         WHERE users.id = OLD.id
--     );
--     DELETE FROM group_request_not WHERE id = (
--         SELECT group_request.id 
--         FROM group_request_not JOIN group_request ON group_request_not.group_request_id = group_request.id
--         JOIN users ON users.id = group_request.user_id
--         WHERE users.id = OLD.id
--     );
--     DELETE FROM friend_request_not WHERE id = (
--         SELECT friend_request.id 
--         FROM friend_request_not JOIN friend_request ON friend_request_not.friend_request = friend_request.id
--         JOIN users ON users.id = friend_request.user_id OR users.id = friend_request.friend_id
--         WHERE users.id = OLD.id
--     );
--     DELETE FROM comment_not WHERE comment_id = (
--         SELECT comment.id 
--         FROM comment_not JOIN comment ON comment_not.comment_id = comment.id
--         JOIN users ON users.id = comment.author
--         WHERE users.id = OLD.id
--     );
--     DELETE FROM reaction_not WHERE reaction_id = (
--         SELECT reaction.id 
--         FROM reaction_not JOIN reaction ON reaction_not.reaction_id = reaction.id
--         JOIN users ON users.id = reaction.author
--         WHERE users.id = OLD.id
--     );
--     UPDATE post SET author = 0 WHERE author = OLD.id;
--     UPDATE comment SET author = 0 WHERE user_id = OLD.id;
--     UPDATE reaction SET author = 0 WHERE user_id = OLD.id;
--     DELETE FROM group_owner WHERE user_id = OLD.id;
--     RETURN OLD;
-- END
-- $BODY$ 
-- LANGUAGE plpgsql;
--
-- CREATE TRIGGER update_deleted_user_trigger
--     AFTER DELETE ON users
--     FOR EACH ROW
--     EXECUTE FUNCTION update_deleted_user();

-----------------------------------------

-- (TRIGGER02) Insert a notification when a comment is made in owner post
CREATE OR REPLACE FUNCTION update_comment_not() RETURNS TRIGGER AS
$BODY$
BEGIN 
    INSERT INTO comment_not (comment_id, date, read) 
    VALUES (NEW.id, now(), false);
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_comment_not_trigger
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE FUNCTION update_comment_not();

-----------------------------------------

-- (TRIGGER03) When a user is tagged, a tagged notification is created
CREATE OR REPLACE FUNCTION update_tag_not() RETURNS TRIGGER AS
$BODY$
BEGIN 
    INSERT INTO post_tag_not (post_tag_id, date) 
    VALUES (NEW.id, now());
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_tag_not_trigger
    AFTER INSERT ON post_tag
    FOR EACH ROW
    EXECUTE FUNCTION update_comment_not();

-----------------------------------------

-- (TRIGGER04) When a reaction is made, a reaction notification is created
CREATE OR REPLACE FUNCTION update_reaction_not() RETURNS TRIGGER AS
$BODY$
BEGIN 
    INSERT INTO reaction_not (reaction_id, date) 
    VALUES (NEW.id, now());
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_reaction_not_trigger
    AFTER INSERT ON reaction
    FOR EACH ROW
    EXECUTE FUNCTION update_reaction_not();


-----------------------------------------

-- (TRIGGER05) When a user receives a group request, a notification is created
CREATE OR REPLACE FUNCTION update_group_request_not() RETURNS TRIGGER AS
$BODY$
BEGIN 
    INSERT INTO group_request_not (group_request_id, date) 
    VALUES (NEW.id, now());
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_group_request_not_trigger
    AFTER INSERT ON group_request
    FOR EACH ROW
    EXECUTE FUNCTION update_group_request_not();

-----------------------------------------

-- (TRIGGER06) When a friend request is added, accepted or declined, a notification will be inserted
CREATE OR REPLACE FUNCTION update_friend_request_not() RETURNS TRIGGER AS
$BODY$
BEGIN 
    INSERT INTO friend_request_not (friend_request, is_accepted, date) 
    VALUES (NEW.id, NEW.is_accepted, now());
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_friend_request_not_trigger
    AFTER INSERT OR UPDATE ON friend_request
    FOR EACH ROW
    EXECUTE FUNCTION update_friend_request_not();

-----------------------------------------

-- (TRIGGER07)  A user can only add posts to groups which he belongs
CREATE OR REPLACE FUNCTION check_belongs_group() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NOT EXISTS (SELECT * FROM group_user where group_user.user_id = NEW.author and group_user.group_id = NEW.group_id)
        AND (NEW.group_id <> null)) THEN
        RAISE EXCEPTION 'The user must belong to the group to add a post';
	END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_belongs_group_trigger
    BEFORE INSERT ON post
    FOR EACH ROW
    EXECUTE FUNCTION check_belongs_group();

-----------------------------------------

-- (TRIGGER08) A user cannot be friend with himself
CREATE OR REPLACE FUNCTION check_friend_himself() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.user_id = NEW.friend_id) THEN
        RAISE EXCEPTION 'A user cannot send friend request to himself.';
	END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_friend_of_himself
    BEFORE INSERT ON friend_request
    EXECUTE FUNCTION check_friend_himself();


-----------------------------------------

-- (TRIGGER09)  A friend request must only be sent to non-friends
CREATE OR REPLACE FUNCTION check_friendship_exists() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM friends where (friend1 = NEW.user_id and friend2 = NEW.friend_id) or 
                                           (friend2 = NEW.user_id and friend1 = NEW.friend_id)) THEN
        RAISE EXCEPTION 'The users are already friends';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_friendship_exists
    BEFORE INSERT ON friend_request
    EXECUTE FUNCTION check_friendship_exists();

-----------------------------------------

-- (TRIGGER10) A group request must only be sent to non-members of group
CREATE OR REPLACE FUNCTION check_group_request() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM group_user where user_id = NEW.user_id and group_id = NEW.group_id) THEN
        RAISE EXCEPTION 'The user is already a member of the group';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_group_request
    BEFORE INSERT ON group_request
    EXECUTE FUNCTION check_group_request();

-----------------------------------------
    
-- (TRIGGER11) When a group request is accepted, the user is added to the groupr
CREATE OR REPLACE FUNCTION add_user_to_group() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_accepted = true THEN
        INSERT INTO group_user (user_id, group_id) VALUES (NEW.user_id, NEW.group_id);
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER add_user_to_group
    AFTER INSERT OR UPDATE ON group_request
    FOR EACH ROW
    WHEN (NEW.is_accepted = true)
    EXECUTE FUNCTION add_user_to_group();

----------------------------------------
    
-- (TRIGGER12) When a friend request is accepted, the users are now friends

CREATE OR REPLACE FUNCTION add_friend() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.is_accepted = true THEN
        INSERT INTO friends (friend1, friend2) VALUES (NEW.user_id, NEW.friend_id);
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER add_friend
    AFTER INSERT OR UPDATE ON friend_request
    FOR EACH ROW
    WHEN (NEW.is_accepted = true)
    EXECUTE FUNCTION add_friend();

    INSERT INTO users (id, username, email, password, academic_status, display_name, is_private, role, image, email_verified_at) VALUES 
        (0, 'deleted_user', 'deleted_user', 'password1', 'Undergraduate', 'John Doe', true, 2, null, '2023-11-23 14:18:29+00'),
        (1, 'johndoe', 'johndoe@example.com', '$2y$10$oI17OO.VH15Kn0i6S840ce6BB.9AH6iAjTfUeCDgz1zVzQbNJ4iiG', 'Undergraduate', 'John Doe', true, 2, 'tHMLkLWZFQhuzM3hSzpOtKsuIMG4X2FcUKrikcGA.png', '2023-11-23 14:18:29+00'),
        (2, 'alanturing', 'alanturing@example.com', 'password2', 'Professor', 'Alan Turing', false, 2, null, '2023-11-23 14:18:29+00'),
        (3, 'adalovelace', 'adalovelace@example.com', 'password3', 'Graduate', 'Ada Lovelace', true, 2, null, '2023-11-23 14:18:29+00'),
        (4, 'admin', 'admin@example.com', '$2y$10$ehcHOK3hnZA7L4h5PvpQge3VfdFbaSxryczs9GzK9lUDNxMcKoWua', 'Administrator', 'Admin User', false, 1, null, '2023-11-23 14:18:29+00');
        -- (5, 'newuser1', 'newuser1@example.com', '$2y$10$3M0VIGcqNMTJ9.PZ8mW3f.9qDokvlX/j64fcsOLtkI8.XyegXzSxC', 'Undergraduate', 'New User 1', false, 2, null, '2023-11-23 14:18:29+00'),
        -- (6, 'newuser2', 'newuser2@example.com', '$2y$10$ZoykCj4aGdzqibHBzqsWUuhu3uVKq.TwRasA5h5HX5OZ/4fA2iJF.', 'Graduate', 'New User 2', true, 2, 'image2.png', '2023-11-23 14:18:29+00'),
        -- (7, 'newuser3', 'newuser3@example.com', '$2y$10$5wUkMCw/ghAB.FTti8Zvh.KZWHzJLOXG3k7FWgMEq8k5IiAAsOaaW', 'Undergraduate', 'New User 3', false, 2, null, '2023-11-23 14:18:29+00'),
        -- (8, 'newuser4', 'newuser4@example.com', '$2y$10$wI4TQbF7SG2G1LyTBRST7.BG2wLOer3ce5HqEXE.AhOlFrfA/HPHK', 'Professor', 'New User 4', true, 2, 'image4.png', '2023-11-23 14:18:29+00'),
        -- (9, 'newuser5', 'newuser5@example.com', '$2y$10$CWM8JizGogNl2iN2LSzTzOa/LcIaTTd6/4hS7LhC16.NfPlndTThK', 'Administrator', 'New User 5', false, 1, null, '2023-11-23 14:18:29+00'),
        -- (10, 'newuser6', 'newuser6@example.com', '$2y$10$Bx/eakJBb2SPhtSCcqYXfeiKZERQJVL6trMs.I1JLCNnA1vBl2lKW', 'Undergraduate', 'New User 6', true, 2, null, '2023-11-23 14:18:29+00'),
        -- (11, 'newuser7', 'newuser7@example.com', '$2y$10$Km.LJSBxGQGYP7bN20.Fd.TZEl4H8DdJbDsDCnZaEjnDUe8JZU3BW', 'Graduate', 'New User 7', false, 2, 'image7.png', '2023-11-23 14:18:29+00'),
        -- (12, 'newuser8', 'newuser8@example.com', '$2y$10$3pCO2UzQYx7gMFCvFwBisegLsPIQ9fHznxADW7NRHRuF9DKd32rSG', 'Undergraduate', 'New User 8', true, 2, null, '2023-11-23 14:18:29+00'),
        -- (13, 'newuser9', 'newuser9@example.com', '$2y$10$jDJzMz27bMlcb2eXYAqzwud8/SowFbSQ0FJ1u/JDAMNwGKUEKGIVS', 'Professor', 'New User 9', false, 2, 'image9.png', '2023-11-23 14:18:29+00'),
        -- (14, 'newuser10', 'newuser10@example.com', '$2y$10$aZqPTzWD4O6ZL20izSRd8eJG0OoRd/uq0cgtcWE0XzRQtBd2PtdKK', 'Administrator', 'New User 10', true, 1, null, '2023-11-23 14:18:29+00'),
        -- (15, 'newuser11', 'newuser11@example.com', '$2y$10$8F8N8h4M/4E1Vdqf6MtJI.2bGfrZ/.cnK/L2J1v7Z7Ei.eMdrXK9W', 'Undergraduate', 'New User 11', false, 2, null, '2023-11-23 14:18:29+00'),
        -- (16, 'newuser12', 'newuser12@example.com', '$2y$10$nJQYy6oaaPfvqBXqCm9BOuWTjWGpHl.PTIvAdKOHWgsOefh21tDku', 'Graduate', 'New User 12', true, 2, 'image12.png', '2023-11-23 14:18:29+00'),
        -- (17, 'newuser13', 'newuser13@example.com', '$2y$10$y4YYy6JfVX7osIgqkm9ydOySb54keCdkSPJ5Iq.9jkmH3ROVt8K1q', 'Undergraduate', 'New User 13', false, 2, null, '2023-11-23 14:18:29+00'),
        -- (18, 'newuser14', 'newuser14@example.com', '$2y$10$Vd11i4f0b3ti7Ct4ihJSE.4j2lelJ43Rm9W10eTRoTPk9zqAGrMZW', 'Professor', 'New User 14', true, 2, 'image14.png', '2023-11-23 14:18:29+00'),
        -- (19, 'newuser15', 'newuser15@example.com', '$2y$10$t/RloAt7nERBfAnC.F6Z6ecUVlMc8GbqzgWt6NMSu7/bfN4TkCvdG', 'Administrator', 'New User 15', false, 1, null, '2023-11-23 14:18:29+00');

    INSERT INTO friend_request(user_id, friend_id, is_accepted, date) VALUES
        (2, 3, null, '1940-01-28 12:00:00'),
        (1, 2, null, '1940-01-28 12:00:00'),
        (1, 4, null, '2023-05-17 15:30:00');
        -- (1, 5, null, '2023-05-17 15:30:00'),
        -- (1, 6, null, '2023-05-17 15:30:00'),
        -- (1, 7, null, '2023-05-17 15:30:00'),
        -- (1, 8, null, '2023-05-17 15:30:00'),
        -- (1, 9, null, '2023-05-17 15:30:00'),
        -- (1, 10, null, '2023-05-17 15:30:00'),
        -- (1, 11, null, '2023-05-17 15:30:00'),
        -- (1, 12, null, '2023-05-17 15:30:00'),
        -- (1, 13, null, '2023-05-17 15:30:00'),
        -- (1, 14, null, '2023-05-17 15:30:00'),
        -- (1, 15, null, '2023-05-17 15:30:00'),
        -- (1, 16, null, '2023-05-17 15:30:00'),
        -- (1, 17, null, '2023-05-17 15:30:00'),
        -- (1, 18, null, '2023-05-17 15:30:00'),
        -- (1, 19, null, '2023-05-17 15:30:00');
    
    UPDATE friend_request SET is_accepted = true WHERE user_id = 2 OR user_id = 1;

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

    INSERT INTO post (author, title, content, attachment, group_id, is_private, date) VALUES
        (1, 'Exciting AI Research Findings', 'Exciting new research findings in the field of artificial intelligence!', 'ai_research.pdf', 1, false, NOW() - INTERVAL '1 day'),
        (2, 'Renewable Energy Discussion', 'Important discussion on renewable energy solutions for the future.', 'renewable_energy.png', 2, true, NOW() - INTERVAL '1 day'),
        (3, 'Quantum Computing Paper Published', 'Just published my new research paper on quantum computing!', 'quantum_paper.pdf', null, false, NOW() - INTERVAL '3 days'),
        (1, 'SpaceXs Mars Colonization Plans', 'Exciting news for all tech enthusiasts - SpaceX plans to colonize Mars!', null, null, true, NOW() - INTERVAL '4 days'),
        (2, 'Exploring 6G Technology', 'Discussing the potential of 6G technology and its impact on communication.', null, 2, false, NOW() - INTERVAL '5 days'),
        (1, 'History of Computer Science Lecture', 'Attended a fascinating lecture on the history of computer science today.', null, 1, false, NOW() - INTERVAL '6 days'),
        (3, 'Challenges in Quantum Computing', 'Exploring the challenges and opportunities in the field of quantum computing.', null, 2, false, NOW() - INTERVAL '7 days'),
        (4, 'Future of AI and Society', 'A sneak peek into the future of AI and its implications for society.', null, null, true, NOW() - INTERVAL '8 days');


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
