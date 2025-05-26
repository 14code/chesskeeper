# ♟️ Chesskeeper

**Chesskeeper** is a lightweight, self-hosted web app for managing personal chess games.  
It's built for fast manual entry and organization of over-the-board tournament games, including image uploads and tagging.

---

## ✅ Features

- PGN import (via file upload or text input)
- Manual game entry and player management
- Upload and assignment of scanned game sheets
- Tagging and commenting for games, players, and tournaments
- Capture photos via smartphone to create games on the go
- Support for user-specific data (`user_id`)
- File-based storage using SQLite (no external DB needed)
- Simple Docker-based setup for development and deployment

---

## 🧱 Tech Stack

- PHP 8.3+ (no framework)
- SQLite
- Plain HTML views (MVC-style)
- Lightweight routing and services

---

## 📦 Installation

```bash
# Build the Docker image
make build

# Start development server with bind-mounted source
make dev
```

---

## 🧪 First-Time Setup (Dev Only)

To reset the database and seed an initial user and player:

```bash
make reset CK_USERNAME=testuser CK_PLAYER_NAME="Test Player"
```

This will:

- Remove all files under `data/`
- Recreate the database (`bin/install.php`)
- Create a user and player (`bin/seed-user.php`)
- Print login credentials to stdout

---

## 📁 Directory Structure

```text
data/
  users/
    1/
      images/        # Uploaded game sheet images
      pgn/           # Optional original PGN files

src/
  Controllers/
  Services/
  ...

views/
  games/
  players/
  tournaments/
  ...

bin/
  install.php        # Creates the SQLite database from schema
  seed-user.php      # Seeds a user + player from CLI
  reset.php          # Resets all data and restarts setup (dev only)
```

---

## 🐳 Docker Usage

### Development (bind-mounts the whole project):

```bash
make dev
```

### Reset environment with a fresh user:

```bash
make reset CK_USERNAME=testuser CK_PLAYER_NAME="Test Player"
```

### Production-like run with persistent data:

```bash
make prod
```

### Shell access for debugging:

```bash
make shell
```

---

## 🧠 Notes

- All data is scoped by `user_id`; currently, user `1` is assumed (single-user mode).
- PGN import strips comments and variations.
- Uploaded files are stored under `data/users/[user_id]/images/`.
- Tagging and comments are supported for games, players, and tournaments.
- Photos can be captured directly from smartphones and submitted for game creation.

---

## 📜 License

MIT — use, modify, and enjoy.
