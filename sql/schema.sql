create schema if not exists lbaw2391;

DROP TABLE IF exists users CASCADE;
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
    academic_status TEXT,
    display_name TEXT,
    is_private BOOLEAN DEFAULT true NOT NULL,
    role INTEGER NOT NULL
);
DROP INDEX IF EXISTS user_index;
CREATE INDEX user_index ON users USING hash(id);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    author INTEGER REFERENCES users(id),
    content TEXT NOT NULL,
    attachment TEXT,
    is_private BOOLEAN NOT NULL,
    date TIMESTAMP WITH TIME ZONE NOT NULL
);
DROP INDEX IF EXISTS author_post;
CREATE INDEX author_post ON post USING btree(author);

CREATE TABLE groups (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT unique_group_name UNIQUE,
    is_private BOOLEAN DEFAULT true NOT NULL,
    description TEXT NOT NULL
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
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL
);

CREATE TABLE group_user(
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    group_id INTEGER REFERENCES groups(id) ON UPDATE CASCADE
);

CREATE TABLE friend_request(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    friend_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    is_accepted BOOLEAN DEFAULT false,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL
);

CREATE TABLE comment(
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    author INTEGER REFERENCES users(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL,
    content TEXT NOT NULL
);

CREATE TABLE reaction (
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    comment_id INTEGER REFERENCES comment(id) ON UPDATE CASCADE,
    author INTEGER REFERENCES users(id),
    type reaction_types NOT NULL,
    CONSTRAINT valid_post_or_comment_ck CHECK(post_id IS NOT NULL or comment_id IS NOT NULL)
);

CREATE TABLE post_tag_not(
    id SERIAL PRIMARY KEY, 
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL
);

CREATE TABLE post_tag(
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE,
    user_id INTEGER REFERENCES users(id) ON UPDATE CASCADE,
);

CREATE TABLE group_request_not(
    id SERIAL PRIMARY KEY, 
    group_request_id INTEGER REFERENCES group_request(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL
);

CREATE TABLE friend_request_not(
    id SERIAL PRIMARY KEY, 
    friend_request INTEGER REFERENCES friend_request(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL
);

CREATE TABLE comment_not(
    id SERIAL PRIMARY KEY, 
    comment_id INTEGER REFERENCES comment(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL
);

CREATE TABLE reaction_not(
    id SERIAL PRIMARY KEY, 
    reaction_id INTEGER REFERENCES reaction(id) ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL
);
