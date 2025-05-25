-- SQLite schema for Chesskeeper (explicit user_id, no defaults)

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL
);

CREATE TABLE players (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    name TEXT NOT NULL,
    fide_id TEXT,
    club TEXT,
    links TEXT
);

CREATE TABLE tournaments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL DEFAULT '',
    location TEXT,
    start_date DATE,
    end_date DATE
);

CREATE TABLE games (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    date DATE NOT NULL,
    white_player_id INTEGER,
    black_player_id INTEGER,
    result REAL NOT NULL DEFAULT 0,
    tournament_id INTEGER,
    round INTEGER,
    moves TEXT NOT NULL DEFAULT '',
    created TEXT DEFAULT (datetime('now'))

);

CREATE TABLE images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    game_id INTEGER,
    image_url TEXT NOT NULL,
    position INTEGER NOT NULL DEFAULT 0
);
