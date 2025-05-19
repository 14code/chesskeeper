-- SQLite schema without DEFAULT 1 for user_id

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL
);

CREATE TABLE players (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    fide_id TEXT,
    club TEXT,
    links TEXT
);

CREATE TABLE tournaments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    location TEXT,
    start_date DATE,
    end_date DATE
);

CREATE TABLE games (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    date DATE NOT NULL,
    white_player_id INTEGER NOT NULL,
    black_player_id INTEGER NOT NULL,
    result REAL NOT NULL,
    tournament_id INTEGER,
    pgn TEXT
);

CREATE TABLE images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    game_id INTEGER NOT NULL,
    image_url TEXT NOT NULL,
    position INTEGER NOT NULL
);
