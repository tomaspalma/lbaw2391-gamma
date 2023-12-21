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
DROP TABLE IF exists polls CASCADE;
DROP TABLE IF exists poll_options CASCADE;
DROP TABLE IF exists poll_option_votes CASCADE;
DROP TABLE IF exists group_invitations CASCADE;
DROP TABLE IF exists group_invitation_nots CASCADE;

-----------------------------------------
-- Types
-----------------------------------------

DROP TYPE if exists reaction_types;
CREATE TYPE reaction_types AS ENUM ('LIKE', 'DISLIKE', 'HEART', 'STAR', 'HANDSHAKE', 'HANDPOINTUP');

-----------------------------------------
-- Tables
-----------------------------------------


CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL CONSTRAINT unique_username UNIQUE,
    email TEXT NOT NULL CONSTRAINT unique_email UNIQUE,
    password TEXT NOT NULL,
    image TEXT,
    academic_status TEXT NOT NULL,
    university TEXT,
    description TEXT,
    display_name TEXT NOT NULL,
    is_private BOOLEAN DEFAULT true NOT NULL,
    role INTEGER NOT NULL,
    email_verified_at TIMESTAMP WITH TIME ZONE DEFAULT now()
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

CREATE TABLE group_invitations(
    id SERIAL PRIMARY KEY,
    owner_id INTEGER REFERENCES users(id),
    user_id INTEGER REFERENCES users(id),
    group_id INTEGER REFERENCES groups(id),
    is_accepted BOOL DEFAULT FALSE
);

CREATE TABLE group_invitation_nots(
    id SERIAL PRIMARY KEY,
    group_invitation_id INTEGER REFERENCES group_invitations(id),
    read BOOLean DEFAULT false,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE polls (
    id SERIAL PRIMARY KEY
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    author INTEGER REFERENCES users(id),
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    attachment TEXT,
    group_id INTEGER REFERENCES groups(id),
    is_private BOOLEAN NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now()),
    poll_id INTEGER REFERENCES polls(id)
);

CREATE TABLE poll_options (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE,
    poll_id INTEGER REFERENCES polls(id)
);

CREATE TABLE poll_option_votes (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    poll_option_id INTEGER REFERENCES poll_options(id),
    poll_id INTEGER REFERENCES polls(id)
);

CREATE TABLE friends (
    friend1 INTEGER REFERENCES users(id),
    friend2 INTEGER REFERENCES users(id),
    PRIMARY KEY (friend1, friend2)
);


CREATE TABLE group_owner(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE
);

CREATE TABLE group_request(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE,
    is_accepted BOOLEAN DEFAULT false NOT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE group_user(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE
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


CREATE TABLE post_tag(
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE
);

CREATE TABLE post_tag_not(
    id SERIAL PRIMARY KEY, 
    post_tag_id INTEGER REFERENCES post_tag(id),
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK (date <= now())
);

CREATE TABLE group_request_not(
    id SERIAL PRIMARY KEY, 
    group_request_id INTEGER REFERENCES group_request(id) ON UPDATE CASCADE ON DELETE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL CHECK(date <= now()),
    read BOOLean DEFAULT false,
    is_acceptance BOOLean DEFAULT false
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

-- (TRIGGER05) When a user receives a group request, a notification is created
CREATE OR REPLACE FUNCTION update_group_acceptance_request_not() RETURNS TRIGGER AS
$BODY$
BEGIN 
    INSERT INTO group_request_not (group_request_id, date, is_acceptance) 
    VALUES (NEW.id, now(), true);
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_group_request_acceptance_not_trigger
    AFTER UPDATE ON group_request
    FOR EACH ROW
    EXECUTE FUNCTION update_group_acceptance_request_not();

-- (TRIGGERXX) WHen a user receives a group invitation, a notification is created

CREATE OR REPLACE FUNCTION create_group_not() RETURNS TRIGGER AS
$BODY$
BEGIN 
    INSERT INTO group_invitation_nots (group_invitation_id, date) 
    VALUES (NEW.id, now());
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER create_group_invite_notification
    AFTER INSERT ON group_invitations
    FOR EACH ROW
    EXECUTE FUNCTION create_group_not();

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

    INSERT INTO users (id, username, email, password, academic_status, display_name, is_private, role, image, email_verified_at, description, university) VALUES 
        (0, 'deleted_user', 'deleted_user', 'password1', 'Undergraduate', 'Deleted User', true, 2, null, '2023-11-23 14:18:29+00', 'A deleted user', 'University of Porto'),
        (1, 'johndoe', 'johndoe@example.com', '$2y$10$oI17OO.VH15Kn0i6S840ce6BB.9AH6iAjTfUeCDgz1zVzQbNJ4iiG', 'Undergraduate', 'John Doe', true, 2, null, '2023-11-23 14:18:29+00', 'An undergraduate student', 'University of Lisboa'),
        (2, 'alanturing', 'alanturing@example.com', '$2y$10$7POXBblYbJue.OPpsNkuyunXqb9QTNabWTp2oEmXIKwO3fPPe4JNq', 'Professor', 'Alan Turing', false, 2, null, '2023-11-23 14:18:29+00', 'A professor in Computer Science', 'University of Cambridge'),
        (3, 'adalovelace', 'adalovelace@example.com', '$2y$10$rOchMCeiruf0E9Z8lPTNme4iraEsKLYeaQnlaPvYqYyJTdjOtFNDC', 'Graduate', 'Ada Lovelace', true, 2, null, '2023-11-23 14:18:29+00', 'A graduate student', 'University of Coimbra'),
        (4, 'admin', 'admin@example.com', '$2y$10$ehcHOK3hnZA7L4h5PvpQge3VfdFbaSxryczs9GzK9lUDNxMcKoWua', 'Undergraduate', 'Admin User', false, 1, null, '2023-11-23 14:18:29+00', 'An undergraduate student', 'University of Porto'),
        (5, 'janedoe', 'janedoe@email.com', '$2y$10$BzWVTUHnVpJoJ87f0trA4O.SbHLsFPCq09f6L.LZjRJe9k7DPUymW', 'Undergraduate', 'Jane Doe', false, 2, null, '2023-11-23 14:18:29+00', 'Computer Science student passionate about AI', 'University of Lisbon'),
        (6, 'bobsmith', 'bobsmith@email.com', '$2y$10$uOs45gJHr7dVg1Hf3PGsVegC21WlWfIcNv.2YXUvmlLdz6yEobP82', 'Graduate', 'Bob Smith', true, 2, null, '2023-11-23 14:18:29+00', 'Environmental Science researcher focusing on sustainability', 'University of Manchester'),
        (7, 'profdoe', 'profdoe@email.com', '$2y$10$TGMZYS9Smbd0I06thU0J.OsFlBxcFArB9rmYZF7mCwvzfFFjKcjXi', 'Professor', 'Professor Doe', false, 2, null, '2023-11-23 14:18:29+00', 'Physics professor with expertise in quantum mechanics', 'University of Oxford'),
        (8, 'emily_jones', 'emily.jones@email.com', '$2y$10$1G8i7XpJbZJ5DvNr5LxqKOcMAs21UZT6dezh2rgYxJG16j.FUsim6', 'Undergraduate', 'Emily Jones', false, 2, null, '2023-11-23 14:18:29+00', 'Chemical Engineering student with a keen interest in sustainable energy solutions. Enjoys experimenting in the lab and volunteering for community projects.', 'Stanford University'),
        (9, 'alex_wong', 'alex.wong@email.com', '$2y$10$r0tWLe4k6eq9CWuI3jXHNeQH5xSof4Q9MPlkiTYj0QjvPPy4/JU8C', 'Graduate', 'Alex Wong', true, 2, null, '2023-11-23 14:18:29+00', 'Ph.D. candidate in Neuroscience conducting research on neuroplasticity. Enthusiastic about science communication and outreach.', 'MIT'),
        (10, 'susan_miller', 'susan.miller@email.com', '$2y$10$ZzvF03t0Nk8DvYTsXw5doeE1qAMxFvBp1yjnI3h7y5zuhQXGqPQDi', 'Professor', 'Dr. Susan Miller', false, 2, null, '2023-11-23 14:18:29+00', 'Professor of History specializing in ancient civilizations. Published author and advocate for inclusive education.', 'Harvard University'),
        (11, 'michael_brown', 'michael.brown@email.com', '$2y$10$2sR8hx1.8K4r.f39XIRG6ePbHCxUpgt2hWc0f9r/kpD3ZClVm3RYG', 'Undergraduate', 'Michael Brown', false, 2, null, '2023-11-23 14:18:29+00', 'Economics major with a passion for data analysis and financial markets. Interned at a leading investment bank.', 'University of Chicago'),
        (12, 'olivia_nguyen', 'olivia.nguyen@email.com', '$2y$10$M0X/X2b5FGFYX8dE.yeOxulbhjA4ItVO5lzSrtPOMeJ1RUWkUysm.', 'Graduate', 'Olivia Nguyen', true, 2, null, '2023-11-23 14:18:29+00', 'Ph.D. candidate in Environmental Science studying the impact of climate change on marine ecosystems. Avid scuba diver and environmental activist.', 'University of California, Berkeley'),
        (13, 'drake_jackson', 'drake.jackson@email.com', '$2y$10$HHTY2ZhnlV2qy9UkAqZc9.GSptYpZ9oZDSjRF/YGXcmZ8Zp0P8o2C', 'Professor', 'Dr. Drake Jackson', false, 2, null, '2023-11-23 14:18:29+00', 'Electrical Engineering professor with expertise in robotics and automation. Leads research projects on autonomous systems.', 'Massachusetts Institute of Technology (MIT)'),
        (14, 'ariana_smith', 'ariana.smith@email.com', '$2y$10$LjOjGLLV3xIuEM8cwDQw7eJ4R/ldFdb5o10hDZDfb3x39zpAJKYKy', 'Undergraduate', 'Ariana Smith', false, 2, null, '2023-11-23 14:18:29+00', 'Psychology major interested in cognitive neuroscience. Actively involved in campus psychology club and mental health awareness initiatives.', 'University of California, Los Angeles (UCLA)'),
        (15, 'sam_garcia', 'sam.garcia@email.com', '$2y$10$Zc.1t/WcAEL.z/0rCzzLRul8T3tpBfG4CxID7.C04u51Kj8sSSjky', 'Undergraduate', 'Sam Garcia', false, 2, null, '2023-11-23 14:18:29+00', 'Business Administration student with a focus on entrepreneurship. Founded a successful startup during sophomore year.', 'Stanford University'),
        (16, 'lily_johnson', 'lily.johnson@email.com', '$2y$10$AKJppGtWU5O.qrQv0zEzkeRjKvSrp8GY/t/F8UwtT4cPO4ckfLvU6', 'Graduate', 'Lily Johnson', true, 2, null, '2023-11-23 14:18:29+00', 'Ph.D. candidate in Linguistics researching language acquisition in multilingual environments. Passionate about cultural diversity and language preservation.', 'University of Toronto'),
        (17, 'prof_anderson', 'prof.anderson@email.com', '$2y$10$VVmy.cRFLFrDAZLZQcQPneS2ywW3e9xZfNOh1K43dd1IEmnZDz9aW', 'Professor', 'Dr. Anderson', false, 2, null, '2023-11-23 14:18:29+00', 'Political Science professor specializing in international relations. Published author and advisor to student-led diplomacy initiatives.', 'Princeton University'),
        (18, 'sophie_miller', 'sophie.miller@email.com', '$2y$10$9iqCOQu5wC3YQRnZfUcQZ.jZGgVkDvcLXsRY/2aGg8lmZlJn1V1KC', 'Undergraduate', 'Sophie Miller', false, 2, null, '2023-11-23 14:18:29+00', 'Art History major with a passion for contemporary art. Curates exhibitions at the campus art gallery and aspires to work in a museum.', 'University of California, San Diego (UCSD)'),
        (19, 'david_jenkins', 'david.jenkins@email.com', '$2y$10$Fn/hfHWJr/OUtq.BQ2eVOu2Oz4tbbE5Tcj9r7ugNwFgoaXcsB5y2G', 'Undergraduate', 'David Jenkins', false, 2, null, '2023-11-23 14:18:29+00', 'Computer Engineering student with a passion for cybersecurity. Participates in hackathons and contributes to open-source projects.', 'University of Texas at Austin'),
        (20, 'natalie_adams', 'natalie.adams@email.com', '$2y$10$Nq/3.DsJuRahNso.ILf26.g/eL2wnXD2fZCIlN6uWMlB9FY.c.HV.', 'Graduate', 'Natalie Adams', true, 2, null, '2023-11-23 14:18:29+00', 'Ph.D. candidate in Biochemistry researching novel cancer therapies. Advocates for women in STEM and mentors undergraduate students.', 'University of California, San Francisco (UCSF)'),
        (21, 'prof_gomez', 'prof.gomez@email.com', '$2y$10$AC9JGseKRUO04Qs6XcPyY.z3p0/4RRsN1q8GvGbpzJI6OuFapAysC', 'Professor', 'Dr. Gomez', false, 2, null, '2023-11-23 14:18:29+00', 'History professor specializing in ancient civilizations and archaeology. Excavation projects include notable historical sites in Greece and Egypt.', 'University of California, Los Angeles (UCLA)'),
        (22, 'emma_wilson', 'emma.wilson@email.com', '$2y$10$3B1tGweYFhnEbz8zRR1K7OFEKEFgF5BZY/6ccEX.QNj/yEE5N9Lwe', 'Undergraduate', 'Emma Wilson', false, 2, null, '2023-11-23 14:18:29+00', 'English Literature major with a focus on contemporary literature. Writes poetry and short stories in free time.', 'University of Oxford');




    INSERT INTO friend_request(user_id, friend_id, is_accepted, date) VALUES
        (2, 3, null, '1940-01-28 12:00:00'),
        (1, 2, null, '1940-01-28 12:00:00'),
        (1, 4, null, '2023-05-17 15:30:00'),
        (1, 5, null, '2023-05-17 15:30:00'),
        (1, 6, null, '2023-05-17 15:30:00'),
        (1, 7, null, '2023-05-17 15:30:00'),
        (1, 8, null, '2023-05-17 15:30:00'),
        (1, 9, null, '2023-05-17 15:30:00'),
        (1, 10, null, '2023-05-17 15:30:00'),
        (1, 11, null, '2023-05-17 15:30:00'),
        (1, 12, null, '2023-05-17 15:30:00'),
        (1, 13, null, '2023-05-17 15:30:00'),
        (1, 14, null, '2023-05-17 15:30:00'),
        (1, 15, null, '2023-05-17 15:30:00'),
        (1, 16, null, '2023-05-17 15:30:00'),
        (1, 17, null, '2023-05-17 15:30:00'),
        (1, 18, null, '2023-05-17 15:30:00'),
        (1, 19, null, '2023-05-17 15:30:00');
    
    UPDATE friend_request SET is_accepted = true WHERE user_id = 2 OR user_id = 1;


    INSERT INTO groups(name, description, is_private) VALUES
    ('Prolog Enthusiasts', 'A community for discussing Prolog programming language and related topics', false),
    ('Tech Enthusiasts', 'A group dedicated to discussing the latest technology trends and innovations', true),
    ('Data Science Club', 'Exploring data science techniques and applications', false),
    ('Programming Beginners', 'Supporting beginners in learning programming', false),
    ('AI Researchers', 'A group for researchers and enthusiasts exploring artificial intelligence and machine learning', false);
    
    INSERT INTO group_user (user_id, group_id) VALUES
        (3, 1),
        (6, 1),
        (7, 1),
        (8, 1),
        (4, 1),
        (10, 3),
        (10, 2),
        (10, 1),
        (10, 4),
        (10, 5),
        (11, 2),
        (12, 3),
        (13, 5),
        (14, 4),
        (15, 1),
        (16, 2),
        (17, 1),
        (18, 2),
        (19, 5);

    INSERT INTO group_owner (group_id, user_id) VALUES
        (1, 1),
        (2, 2),
        (2, 1);

    INSERT INTO group_request(user_id, group_id, is_accepted, date) VALUES
        (4, 2, true, '2023-08-01 12:00:00'),
        (4, 2, false, '2023-08-01 12:00:00'),
        (5, 2, false, '2023-08-01 12:00:00'),
        (9, 2, false, '2023-08-01 12:00:00'),
        (10, 2, false, '2023-08-01 12:00:00'),
        (11, 2, false, '2023-08-01 12:00:00'),
        (12, 2, false, '2023-08-01 12:00:00'),
        (13, 2, false, '2023-08-01 12:00:00'),
        (14, 2, false, '2023-08-01 12:00:00'),
        (16, 2, false, '2023-08-01 12:00:00'),
        (18, 2, false, '2023-08-01 12:00:00'),
        (19, 2, false, '2023-08-01 12:00:00');

    INSERT INTO post (author, title, content, attachment, group_id, is_private, date) VALUES
        (1, 'Exciting AI Research Findings', 'Our research team has been hard at work exploring the frontiers of artificial intelligence, and I am thrilled to share some exciting findings with you all. In our recent endeavors, we have made substantial progress in the realm of natural language processing. The teams focus on enhancing the capabilities of neural networks has yielded remarkable results. Through innovative approaches, we have achieved notable advancements in language understanding and generation. These breakthroughs have the potential to revolutionize how machines comprehend and generate human-like text. One of our key achievements lies in the domain of unsupervised learning. By developing novel techniques, we have empowered machine learning models to generalize better and adapt to a diverse array of tasks. This adaptability is a crucial step toward creating more versatile and efficient AI systems. Our commitment to transparency and accountability has led us to explore interpretability in AI models. We have developed methods to unravel the decision-making processes of complex neural networks, ensuring that these systems are not only powerful but also understandable. Ethical considerations are at the forefront of our research. We recognize the societal impact of AI and are actively working on addressing issues related to fairness, bias, and the responsible deployment of artificial intelligence. I invite you all to engage in a discussion about these findings. Your thoughts, questions, and insights are invaluable as we navigate the evolving landscape of AI together.',null, 1, false, NOW() - INTERVAL '1 day'),
        (2, 'Renewable Energy Discussion', 'Important discussion on renewable energy solutions for the future. From advanced solar technologies to innovative wind power solutions, lets explore the possibilities and challenges of sustainable energy.',null, 1, false, NOW() - INTERVAL '1 day'),
        (3, 'Quantum Computing Paper Published', 'Thrilled to announce the publication of my latest research paper on quantum computing! Delving into the fascinating world of quantum algorithms, entanglement phenomena, and the potential applications in solving complex problems. The paper explores groundbreaking concepts in quantum information processing and sheds light on the latest advancements in the field. If you are interested in the future of computing, feel free to check out the full paper: [Quantum Paper](quantum_paper.pdf)', null, null, false, NOW() - INTERVAL '3 days'),
        (1, 'SpaceXs Mars Colonization Plans', 'Exciting developments for all tech enthusiasts! SpaceX has recently unveiled ambitious plans to colonize Mars, marking a significant leap in space exploration. Join the conversation to explore the challenges, opportunities, and the potential impact of this groundbreaking initiative.', null, null, true, NOW() - INTERVAL '4 days'),
        (2, 'Exploring 6G Technology', 'Embark on a journey into the transformative potential of 6G technology, exploring faster data speeds, ultra-reliable low latency communication (URLLC), massive machine-type communications (mMTC), and its impact on AR, VR, IoT, and various industries. Share your insights on evolving standards, potential disruptions, and innovations. What specific areas do you believe 6G will impact the most, and how might it influence the technological landscape in the years to come?', null, 2, false, NOW() - INTERVAL '5 days'), 
        (1, 'History of Computer Science Lecture', 'Immersed myself in an enlightening lecture today, tracing the captivating journey of computer science through the annals of time. Explored the origins of algorithms, pondered over the brilliance of Ada Lovelace, and marveled at the groundbreaking innovations from Turing to modern computing. From the birth of the first computers to the evolution of programming languages, the lecture painted a vivid picture of the pioneers and their groundbreaking contributions. The anecdotes about early computational devices and the challenges faced by trailblazers in the field added depth to the narrative. Delving into the lectures discussion on the impact of computers on society, I could not help but reflect on the transformative power of technology. The intricate dance between hardware and software unfolded, revealing the interconnected web of advancements that have shaped our digital world. Excited to share and discuss these insights with fellow enthusiasts! What aspects of the history of computer science fascinate you the most, and how do you see its influence shaping our future?', null, 1, false, NOW() - INTERVAL '6 days'),
        (3, 'Challenges in Quantum Computing', 'Embark on a deep exploration of the intricate landscape of quantum computing, dissecting the myriad challenges and opportunities that define this cutting-edge field. Share your insights on quantum decoherence, noise, scalable quantum hardware, and the transformative potential of quantum computing in reshaping computation and problem-solving.', null, 2, false, NOW() - INTERVAL '7 days'),
        (4, 'Future of AI and Society', 'Embark on an intriguing exploration of the future landscape shaped by artificial intelligence, unraveling the multifaceted implications for society. Dive deep into the ethical dimensions of AI, examining its potential impact on employment dynamics, privacy, and the distribution of wealth. As we navigate the path ahead, lets delve into the societal transformations driven by intelligent automation and machine learning algorithms. Explore the democratization of knowledge, the evolution of education, and the ethical considerations inherent in AI-driven decision-making processes. Reflecting on the delicate balance between innovation and responsibility, we invite you to share your insights on crafting ethical frameworks, policy considerations, and collaborative efforts needed to ensure a harmonious integration of AI into our social fabric. Join the discourse and contribute to the dialogue on the opportunities, challenges, and ethical contours that will define the intricate relationship between AI and society in the years to come.', null, null, true, NOW() - INTERVAL '8 days'),
        (5, 'Diversity in Tech Panel', 'Immersed in a dynamic panel discussion on championing diversity and inclusion in the ever-evolving tech industry. Explored nuanced strategies to dismantle barriers for underrepresented groups, addressing gender and cultural biases. Engaged in dialogue about the transformative power of mentorship programs and the role of inclusive work environments in fostering innovation. Delving deeper, we navigated the challenges faced by individuals from diverse backgrounds in the tech sector, brainstorming actionable solutions to bridge gaps and promote equal opportunities. Explored the significance of tackling unconscious biases, promoting educational initiatives, and empowering marginalized voices to drive positive change. This panel aimed not only to shed light on the existing disparities but also to inspire tangible actions for a more inclusive tech future. Your unique insights on diversity, inclusion, and the ongoing efforts to reshape the industry are essential. Join the ongoing conversation and share your experiences, ideas, and strategies for cultivating a truly diverse and equitable tech landscape.', null, 2, false, NOW() - INTERVAL '9 days'),
        (6, 'Environmental Conservation Initiatives', 'Dive into a discourse on environmental conservation initiatives, sharing insights on ongoing projects and their profound impact on biodiversity, ecosystems, and sustainability. Join the conversation to explore strategies, challenges, and the collective responsibility in preserving our planet.', null, 3, false, NOW() - INTERVAL '10 days'),
        (7, 'Introduction to Python Programming', 'Embarked on an extensive and user-friendly series designed to demystify Python programming for beginners. From mastering the basics of syntax to exploring real-world applications, the series is a comprehensive guide to kickstart your coding journey. Join our vibrant community, engage in hands-on exercises, and let the excitement of learning Python unfold together!', null, 4, false, NOW() - INTERVAL '11 days'),
        (8, 'Exploring Ancient Civilizations', 'Embarking on a captivating odyssey to unravel the mysteries of ancient civilizations. From deciphering enigmatic hieroglyphics to uncovering archaeological wonders, join me in delving into the rich tapestry of human history. Together, lets explore the cultural, technological, and artistic contributions that have left an indelible mark on the evolution of our civilization.', null, null, false, NOW() - INTERVAL '12 days'),
        (9, 'Neuroscience Research Breakthrough', 'Embarking on a journey through recent neuroscience breakthroughs, unveiling their potential applications in understanding the complexities of the brain. Join the discussion to delve into the implications, advancements, and the future landscape of neuroscientific research.', null, 5, false, NOW() - INTERVAL '13 days'),
        (10, 'Art and Creativity Workshop', 'Organizing a workshop on the intersection of technology and art. Join us for a creative session!', null, 2, false, NOW() - INTERVAL '14 days'),
        (11, 'Space Exploration Documentary', 'Watched an inspiring documentary on the latest achievements in space exploration.', null, null, true, NOW() - INTERVAL '15 days'),
        (12, 'Chemical Engineering Innovations', 'Highlighting recent innovations in chemical engineering and their impact on sustainable practices.', null, 5, false, NOW() - INTERVAL '16 days'),
        (13, 'STEM Education Symposium', 'Attended a STEM education symposium discussing ways to enhance science, technology, engineering, and math education.', null, null, false, NOW() - INTERVAL '17 days'),
        (14, 'Psychology and Well-being', 'Reflecting on the connection between psychology and overall well-being. Discuss your thoughts and experiences!', null, null, true, NOW() - INTERVAL '18 days'),
        (15, 'Business Startups Networking Event', 'Exciting networking event for aspiring entrepreneurs. Share your startup journey and connect with others!', null, 2, false, NOW() - INTERVAL '19 days'),
        (16, 'Linguistics Research Update', 'Providing an update on my current research in linguistics and language acquisition.', null, 3, false, NOW() - INTERVAL '20 days'),
        (17, 'Political Science Discussion', 'Initiating a discussion on recent political developments and their global implications.', null, 1, false, NOW() - INTERVAL '21 days'),
        (18, 'Contemporary Art Exhibition', 'Visited a fascinating contemporary art exhibition. Sharing my favorite pieces and interpretations!', null, null, false, NOW() - INTERVAL '22 days'),
        (19, 'Cybersecurity Tips and Tricks', 'Sharing practical tips and tricks for enhancing cybersecurity. Stay safe online!', null, 2, false, NOW() - INTERVAL '23 days'),
        (12, 'Biochemistry Breakthrough', 'Exciting breakthrough in biochemistry research. Discussing its potential applications in healthcare.', null, 3, false, NOW() - INTERVAL '24 days'),
        (15, 'Ancient History Lecture Series', 'Announcing a lecture series on ancient history. Join us for an exploration of civilizations past!', null, 1, false, NOW() - INTERVAL '25 days'),
        (16, 'Robotics and Automation Showcase', 'Showcasing the latest advancements in robotics and automation. Discussing the future of intelligent machines.', null, null, false, NOW() - INTERVAL '26 days'),
        (13, 'Literary Analysis Discussion', 'Initiating a discussion on literary analysis and interpretation. Share your favorite literary works and insights!', null, null, true, NOW() - INTERVAL '27 days'),
        (12, 'Computer Science Internship Experience', 'Reflecting on my recent computer science internship experience. Offering advice to fellow students entering the industry.', null, 2, false, NOW() - INTERVAL '28 days'),
        (11, 'Marine Ecosystem Conservation', 'Discussing ongoing efforts in marine ecosystem conservation and the importance of protecting our oceans.', null, 3, false, NOW() - INTERVAL '29 days'),
        (7, 'Quantum Mechanics Workshop', 'Organizing a workshop on quantum mechanics. Join us for an interactive session on this fascinating topic!', null, 1, false, NOW() - INTERVAL '30 days'),
        (1, 'Economic Trends Discussion', 'Initiating a discussion on current economic trends and their impact on global markets.', null, 4, false, NOW() - INTERVAL '31 days'),
        (2, 'Environmental Art Exhibition', 'Visited an art exhibition dedicated to environmental themes. Sharing powerful artworks that convey environmental messages.', null, null, false, NOW() - INTERVAL '32 days'),
        (3, 'Blockchain and Cryptocurrency Forum', 'Starting a forum to discuss the latest trends and developments in blockchain and cryptocurrency.', null, 2, false, NOW() - INTERVAL '33 days'),
        (20, 'Neuroplasticity Seminar', 'Hosting a seminar on neuroplasticity and its implications for brain health. Join the conversation!', null, 5, false, NOW() - INTERVAL '34 days'),
        (22, 'Future of Electrical Engineering', 'Discussing the future of electrical engineering and emerging technologies in the field.', null, null, false, NOW() - INTERVAL '36 days'),
        (17, 'Creative Writing Showcase', 'Showcasing creative writing pieces from our community. Share your poems, stories, and more!', null, null, true, NOW() - INTERVAL '37 days'),
        (18, 'Data Analysis Challenges', 'Announcing a series of data analysis challenges. Sharpen your analytical skills and collaborate with fellow data enthusiasts!', null, 3, false, NOW() - INTERVAL '38 days'),
        (5, 'Sustainable Energy Solutions Forum', 'Exploring sustainable energy solutions and discussing ways to address climate change through innovative technologies.', null, 4, false, NOW() - INTERVAL '39 days');


    INSERT INTO comment (id, post_id, author, content, date) VALUES
        (1, 1, 3, 'This is amazing! Can you share more details about the AI research findings?', NOW() - INTERVAL '1 day'),
        (2, 1, 4, 'Im eager to read the research paper. Please share the link when its available!', NOW() - INTERVAL '23 hours'),
        (3, 2, 1, 'Renewable energy is the future, and we need to invest more in it.', NOW() - INTERVAL '1 day'),
        (4, 4, 3, 'The smartphone industry is advancing rapidly. Any thoughts on sustainability?', NOW() - INTERVAL '2 days'),
        (5, 7, 2, 'AIs impact on society is a crucial discussion. Lets explore it further.', NOW() - INTERVAL '2 days'),
        (6, 8, 4, 'Python 4.0 sounds exciting! What are the new features in this version?', NOW() - INTERVAL '3 days'),
        (7, 5, 1, 'Id love to hear more about the history of computer science. Please share insights!', NOW() - INTERVAL '4 days'),
        (8, 3, 2, 'Congratulations on your new research paper on quantum computing!', NOW() - INTERVAL '4 days'),
        (9, 6, 1, 'Im excited about the future of space exploration. What do you think will be the next big milestone?', NOW() - INTERVAL '1 day'),
        (10, 6, 2, 'I agree! The potential for discoveries is immense. Maybe well find signs of extraterrestrial life soon.', NOW() - INTERVAL '22 hours'),
        (11, 6, 1, 'That would be groundbreaking! Do you think the advancements in AI will play a role in analyzing potential signals from outer space?', NOW() - INTERVAL '18 hours'),
        (12, 6, 2, 'Absolutely! AI could significantly enhance our ability to process and interpret vast amounts of data. Its an exciting time for interdisciplinary research.', NOW() - INTERVAL '15 hours'),
        (13, 6, 1, 'Speaking of interdisciplinary research, do you think the collaboration between space agencies and private companies will accelerate space exploration?', NOW() - INTERVAL '10 hours'),
        (14, 6, 2, 'I believe so. Private companies bring innovation and resources, which can complement the efforts of traditional space agencies. Whats your take on the role of international collaboration in this context?', NOW() - INTERVAL '8 hours'),
        (15, 6, 1, 'International collaboration is crucial. It allows pooling of expertise and resources, making space exploration a collective human effort. Plus, it fosters peaceful cooperation on a global scale.', NOW() - INTERVAL '6 hours'),
        (16, 6, 2, 'Well said! Its inspiring to see how space exploration brings people together. What specific areas of space research do you find most fascinating?', NOW() - INTERVAL '4 hours'),
        (17, 6, 1, 'Personally, Im fascinated by the search for exoplanets and the possibility of finding habitable worlds. The idea that we might not be alone in the universe is truly awe-inspiring. How about you?', NOW() - INTERVAL '2 hours'),
        (18, 6, 2, 'I share your fascination with exoplanets. Discovering other potentially habitable planets could reshape our understanding of life in the cosmos. Its an exciting time to be curious about the universe!', NOW() - INTERVAL '1 hour'),
        (19, 6, 1, 'Absolutely, the advancements were witnessing in space exploration are truly groundbreaking. It sparks the imagination and opens up new possibilities for the future. What do you think the next major space mission should focus on?', NOW() - INTERVAL '30 minutes'),
        (20, 6, 2, 'Thats a great question! I think a mission dedicated to exploring the icy moons of Jupiter, like Europa or Ganymede, could provide valuable insights into the potential for life beyond Earth. Whats your take on it?', NOW() - INTERVAL '15 minutes'),
        (21, 6, 1, 'Exploring the icy moons is an excellent choice. The subsurface oceans of these moons could harbor unique forms of life. Im excited about the prospect of discovering extraterrestrial life within our own solar system. Thanks for the engaging conversation!', NOW() - INTERVAL '5 minutes');


    INSERT INTO reaction (post_id, comment_id ,author, type) VALUES
        (1, NULL, 3, 'LIKE'),
        (NULL, 1, 2, 'HEART'),
        (1, NULL, 1, 'LIKE'),
        (3, NULL, 2, 'HEART'), 
        (NULL, 5, 4, 'STAR'),
        (6, NULL, 3, 'DISLIKE'),
        (NULL, 7, 2, 'DISLIKE');
