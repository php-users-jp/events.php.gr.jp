CREATE TABLE event (
    id INTEGER NOT NULL PRIMARY KEY,
    author VARCHAR,
    name VARCHAR(64),
    max_register INTEGER,
    description VARCHAR,
    private_description VARCHAR,
    map VARCHAR,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    due_date TIMESTAMP,
    publish_date TIMESTAMP,
    accept_date TIMESTAMP,
    private INT DEFAULT '0'
);  

CREATE TABLE event_attendee (
                id INTEGER NOT NULL PRIMARY KEY,
                event_id INTEGER,
                user_id INTEGER,
                comment VARCHAR,
                party INTEGER DEFAULT "0",
                canceled INTEGER,
                created TIMESTAMP,
                modified TIMESTAMP );

CREATE TABLE event_comment (
 id INTEGER NOT NULL PRIMARY KEY,
 event_id INTEGER,
 user_id INTEGER,
 comment VARCHAR,
 created TIMESTAMP,
 ip VARCHAR,
 ua VARCHAR
);

CREATE TABLE event_page (
                id       INTEGER NOT NULL PRIMARY KEY,
                event_id INTEGER,
                user_id  INTEGER,
                content  VARCHAR,
                created  TIMESTAMP);

CREATE TABLE system (
                id INTEGER NOT NULL PRIMARY KEY,
                v_column VARCHAR,
                v_value VARCHAR,
                modified TIMESTAMP );

CREATE TABLE trackback (
                id           INTEGER NOT NULL PRIMARY KEY,
                event_id     INTEGER,
                url          VARCHAR,
                title        VARCHAR,
                excerpt      VARCHAR,
                blog_name    VARCHAR,
                receive_time TIMESTAMP,
                remote_addr  VARCHAR);

CREATE TABLE user(
 id INTEGER NOT NULL PRIMARY KEY,
 nickname VARCHAR,
 username VARCHAR,
 password VARCHAR,
 provider_url VACHAR,
 role VARCHAR,
 created TIMESTAMP,
 modified TIMESTAMP
);
