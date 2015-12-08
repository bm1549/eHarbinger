-- we define a username to be unique
-- are we going to use email?
CREATE TABLE users(
	username varchar(255) PRIMARY KEY,
	password varchar(255) NOT NULL,
	loginTimestamp timestamp DEFAULT current_timestamp
);

-- public profile information
-- What did we have here again?
CREATE TABLE users_public(
	username varchar(255) REFERENCES users,
	email varchar(255) NOT NULL,
	name text DEFAULT '',
	location text DEFAULT '',
	languages text DEFAULT '',
	description text DEFAULT ''
);

-- users message other users
CREATE TABLE users_message_users(
	username1 varchar(255) REFERENCES users (username),
	username2 varchar(255) REFERENCES users (username),
	message text,
	messageTimestamp timestamp DEFAULT current_timestamp
);

-- questionId is generated by an auto-increment macro
-- allow psql to use default value in all inserts
CREATE TABLE questions(
	questionId serial PRIMARY KEY,
	questionText text NOT NULL
);

-- Answer (even with multiple choices) can be encoded in integer
-- For 5 answers, yes yes no no no -> 11000
-- Not sure if this representation is viable
-- Will work on more in future
CREATE TABLE users_answer_questions(
	username varchar(255) REFERENCES users,
	questionId integer REFERENCES questions,
	answerSelf integer NOT NULL,
	answerOther integer NOT NULL,
	importance integer NOT NULL,
	CHECK( importance BETWEEN 0 and 5 )	
);

-- needs gameId and gameConsole. Description optional
CREATE TABLE games(
	gameName varchar(255) NOT NULL,
	gameConsole varchar(255) NOT NULL,
	PRIMARY KEY( gameName, gameConsole ),
	gameDesc text
);

-- a user can only 'like' a game once
CREATE TABLE users_like_games(
	username varchar(255) REFERENCES users,
	gameName varchar(255),
	gameConsole varchar(255),
	FOREIGN KEY( gameName, gameConsole ) REFERENCES games,
	UNIQUE( username, gameName, gameConsole )
);

-- a user can only rate another user once
-- a user cannot rate themselves 
CREATE TABLE users_rate_users(
	username1 varchar(255) REFERENCES users (username),
	username2 varchar(255) REFERENCES users (username),
	CHECK ( username1 != username2 ),
	rating integer,
	CHECK (rating = 1 OR rating = -1),
	PRIMARY KEY( username1, username2 )
);

-- Store username in forums table
-- Needs subject and body (can change)
-- timestamp created automatically
CREATE TABLE forums(
	forumId serial PRIMARY KEY,
	username varchar(255) REFERENCES users,
	forumSubj varchar(255) NOT NULL,
	forumBody text NOT NULL,
	forumTimestamp timestamp DEFAULT current_timestamp
);

-- Store username and forumId in forums_comment
-- Needs body
CREATE TABLE forums_comment(
	commentId serial PRIMARY KEY,
	forumId integer REFERENCES forums,
	username varchar(255) REFERENCES users,
	commentBody text NOT NULL,
	commentTimestamp timestamp DEFAULT current_timestamp
);

-- Users match with each other
-- time and match percentage are stored
CREATE TABLE users_match_users(
	username1 varchar(255) REFERENCES users (username),
	username2 varchar(255) REFERENCES users (username),
	matchPercent int NOT NULL,
	CHECK( matchPercent BETWEEN 0 and 100 ),
	hidden boolean DEFAULT false,
	matchTimestamp timestamp DEFAULT current_timestamp
);

