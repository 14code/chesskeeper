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

## 🗃️ Database Schema & Migration

Chesskeeper uses a simple SQLite-based schema located in:

```
sql/schema.sql
```

This file defines the initial database structure for clean setups (e.g. during `make reset` or install).

### 🔄 Migrating Existing Databases

Schema changes (e.g. adding new fields) should be added to:

```
bin/migrate.php
```

This script is **idempotent** and will only apply necessary changes (e.g. missing columns) without affecting existing data.

Run it anytime after a `git pull`:

```bash
make migrate
```

This will:
- Backup the current database
- Limit backups to the 5 most recent `.bak` files
- Apply all defined migrations

Alternatively:

```bash
php bin/migrate.php
```

### 💾 Backup & Versioned History

Run this manually to create a snapshot:

```bash
make backup
```

It will create a file like:
```
data/chesskeeper-2025-05-28_18-45-12.bak
```

To keep storage clean, run:

```bash
make limit-backups
```

Or let it run automatically with `make migrate`.

### ✏ Example: Adding a new column

To add a column `notes` to the `games` table, edit `bin/migrate.php`:

```php
if (!hasColumn($pdo, 'games', 'notes')) {
    $pdo->exec("ALTER TABLE games ADD COLUMN notes TEXT");
    echo "✔ Added 'notes' column to games\n";
}
```

---

## 🚀 Releases & Versioning

This project follows semantic versioning:

- **`v0.1.0`** – Initial MVP (game management, PGN parsing, image uploads)
- **`v0.2.0`** – Import enhancements, player/tournament linkage
- **`v0.3.0`** – Interactive PGN recorder, board preview, Viewer.js integration
- **`v0.4.0` (upcoming)** – Filter & export by player/color, persistent migration strategy, tags/comments

To tag a new version:

```bash
git tag v0.4.0
git push origin v0.4.0
```

You can review all tags with:

```bash
git tag
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
