BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "users" (
	"id"	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	"name"	varchar NOT NULL,
	"email"	varchar NOT NULL UNIQUE,
	"email_verified_at"	datetime,
	"password"	varchar NOT NULL,
	"remember_token"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime
);
CREATE TABLE IF NOT EXISTS "users_scores" (
	"id"	INTEGER,
	"wins"	INTEGER DEFAULT 0,
	"losses"	INTEGER DEFAULT 0,
	PRIMARY KEY("id")
);
CREATE TABLE IF NOT EXISTS "hangman_images" (
	"lives"	INTEGER,
	"data"	BLOB
);
CREATE TABLE IF NOT EXISTS "users_words" (
	"user_id"	INTEGER,
	"word"	TEXT,
	"frontend_word"	TEXT,
	"lives"	INTEGER DEFAULT 12,
	"blacklist"	TEXT,
	"prefered_language"	TEXT DEFAULT 'en'
);
CREATE TABLE IF NOT EXISTS "words" (
	"id"	INTEGER PRIMARY KEY AUTOINCREMENT,
	"word"	TEXT,
	"language"	TEXT DEFAULT 'en'
);
CREATE TABLE IF NOT EXISTS "failed_jobs" (
	"id"	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	"connection"	text NOT NULL,
	"queue"	text NOT NULL,
	"payload"	text NOT NULL,
	"exception"	text NOT NULL,
	"failed_at"	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS "oauth_personal_access_clients" (
	"id"	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	"client_id"	integer NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime
);
CREATE TABLE IF NOT EXISTS "oauth_clients" (
	"id"	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	"user_id"	integer,
	"name"	varchar NOT NULL,
	"secret"	varchar NOT NULL,
	"redirect"	text NOT NULL,
	"personal_access_client"	tinyint(1) NOT NULL,
	"password_client"	tinyint(1) NOT NULL,
	"revoked"	tinyint(1) NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime
);
CREATE TABLE IF NOT EXISTS "oauth_refresh_tokens" (
	"id"	varchar NOT NULL,
	"access_token_id"	varchar NOT NULL,
	"revoked"	tinyint(1) NOT NULL,
	"expires_at"	datetime,
	PRIMARY KEY("id")
);
CREATE TABLE IF NOT EXISTS "oauth_access_tokens" (
	"id"	varchar NOT NULL,
	"user_id"	integer,
	"client_id"	integer NOT NULL,
	"name"	varchar,
	"scopes"	text,
	"revoked"	tinyint(1) NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	"expires_at"	datetime,
	PRIMARY KEY("id")
);
CREATE TABLE IF NOT EXISTS "oauth_auth_codes" (
	"id"	varchar NOT NULL,
	"user_id"	integer NOT NULL,
	"client_id"	integer NOT NULL,
	"scopes"	text,
	"revoked"	tinyint(1) NOT NULL,
	"expires_at"	datetime,
	PRIMARY KEY("id")
);
CREATE TABLE IF NOT EXISTS "password_resets" (
	"email"	varchar NOT NULL,
	"token"	varchar NOT NULL,
	"created_at"	datetime
);
CREATE TABLE IF NOT EXISTS "migrations" (
	"id"	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	"migration"	varchar NOT NULL,
	"batch"	integer NOT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS "users_email_unique" ON "users" (
	"email"
);
CREATE INDEX IF NOT EXISTS "oauth_personal_access_clients_client_id_index" ON "oauth_personal_access_clients" (
	"client_id"
);
CREATE INDEX IF NOT EXISTS "oauth_clients_user_id_index" ON "oauth_clients" (
	"user_id"
);
CREATE INDEX IF NOT EXISTS "oauth_refresh_tokens_access_token_id_index" ON "oauth_refresh_tokens" (
	"access_token_id"
);
CREATE INDEX IF NOT EXISTS "oauth_access_tokens_user_id_index" ON "oauth_access_tokens" (
	"user_id"
);
CREATE INDEX IF NOT EXISTS "password_resets_email_index" ON "password_resets" (
	"email"
);
COMMIT;
