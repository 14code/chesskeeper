-- Datenbankschema mit players, games, tournaments, images

CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    fide_id VARCHAR(20),
    club VARCHAR(255),
    links JSON
);

CREATE TABLE tournaments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    start_date DATE,
    end_date DATE
);

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    white_player_id INT NOT NULL,
    black_player_id INT NOT NULL,
    result DECIMAL(2,1) NOT NULL,
    tournament_id INT,
    pgn TEXT,
    FOREIGN KEY (white_player_id) REFERENCES players(id),
    FOREIGN KEY (black_player_id) REFERENCES players(id),
    FOREIGN KEY (tournament_id) REFERENCES tournaments(id)
);

CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    image_url VARCHAR(1024) NOT NULL,
    position INT NOT NULL,
    FOREIGN KEY (game_id) REFERENCES games(id)
);
