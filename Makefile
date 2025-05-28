# Chesskeeper Makefile

# Base configuration
IMAGE_NAME = chesskeeper
CONTAINER_NAME = chesskeeper
PORT = 8080

check-env:
	@command -v composer >/dev/null 2>&1 || echo "⚠️  Composer not found!"
	@command -v npm >/dev/null 2>&1 || echo "⚠️  npm not found!"

# Docker build
build:
	docker build -t $(IMAGE_NAME) .

# Development mode with bind mount
dev:
	docker run -d --rm \
		-v "$$(pwd)":/app \
		-p $(PORT):8080 \
		--name $(CONTAINER_NAME) \
		$(IMAGE_NAME)

# Production mode with persistent data
prod:
	docker run -d \
		-v "$$(pwd)/data":/app/data \
		-p $(PORT):8080 \
		--name $(CONTAINER_NAME) \
		$(IMAGE_NAME)

# Reset using environment variables and bin/reset.php
reset:
	docker run -it --rm \
		-v "$$(pwd)/data":/app/data \
		-w /app \
		-e CK_USERNAME="$(CK_USERNAME)" \
		-e CK_PASSWORD="$(CK_PASSWORD)" \
		-e CK_PLAYER_NAME="$(CK_PLAYER_NAME)" \
		$(IMAGE_NAME) \
		php bin/reset.php

# Open interactive shell inside container
shell:
	docker run -it --rm \
		-v "$$(pwd)":/app \
		-w /app \
		--entrypoint sh \
		$(IMAGE_NAME)

# Install NPM dependencies
npm:
	@if command -v npm >/dev/null 2>&1; then \
		echo "📦 Installing NPM dependencies..."; \
		npm install; \
	else \
		echo "⚠️  npm not found. Skipping npm install."; \
	fi

# Copy required JS/CSS assets to public directory
assets: npm autoload
	mkdir -p public/vendor/jquery
	cp node_modules/jquery/dist/jquery.min.js public/vendor/jquery
	mkdir -p public/vendor/chess
	cp node_modules/chess.js/chess.min.js public/vendor/chess/
	cp node_modules/@chrisoakman/chessboardjs/dist/chessboard-1.0.0.min.* public/vendor/chess/

# Download all 12 piece images (Wikipedia style)
fetch-pieces:
	mkdir -p public/assets/chess/pieces/wikipedia
	curl -s -o public/assets/chess/pieces/wikipedia/wK.png https://upload.wikimedia.org/wikipedia/commons/4/42/Chess_klt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/wQ.png https://upload.wikimedia.org/wikipedia/commons/1/15/Chess_qlt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/wR.png https://upload.wikimedia.org/wikipedia/commons/7/72/Chess_rlt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/wB.png https://upload.wikimedia.org/wikipedia/commons/b/b1/Chess_blt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/wN.png https://upload.wikimedia.org/wikipedia/commons/7/70/Chess_nlt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/wP.png https://upload.wikimedia.org/wikipedia/commons/4/45/Chess_plt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/bK.png https://upload.wikimedia.org/wikipedia/commons/f/f0/Chess_kdt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/bQ.png https://upload.wikimedia.org/wikipedia/commons/4/47/Chess_qdt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/bR.png https://upload.wikimedia.org/wikipedia/commons/f/ff/Chess_rdt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/bB.png https://upload.wikimedia.org/wikipedia/commons/9/98/Chess_bdt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/bN.png https://upload.wikimedia.org/wikipedia/commons/e/ef/Chess_ndt45.png
	curl -s -o public/assets/chess/pieces/wikipedia/bP.png https://upload.wikimedia.org/wikipedia/commons/c/c7/Chess_pdt45.png

fetch-pieces-svg:
	mkdir -p public/assets/chess/pieces/cburnett
	curl -s -o public/assets/chess/pieces/cburnett/wK.svg https://upload.wikimedia.org/wikipedia/commons/4/42/Chess_klt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/wQ.svg https://upload.wikimedia.org/wikipedia/commons/1/15/Chess_qlt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/wR.svg https://upload.wikimedia.org/wikipedia/commons/7/72/Chess_rlt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/wB.svg https://upload.wikimedia.org/wikipedia/commons/b/b1/Chess_blt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/wN.svg https://upload.wikimedia.org/wikipedia/commons/7/70/Chess_nlt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/wP.svg https://upload.wikimedia.org/wikipedia/commons/4/45/Chess_plt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/bK.svg https://upload.wikimedia.org/wikipedia/commons/f/f0/Chess_kdt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/bQ.svg https://upload.wikimedia.org/wikipedia/commons/4/47/Chess_qdt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/bR.svg https://upload.wikimedia.org/wikipedia/commons/f/ff/Chess_rdt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/bB.svg https://upload.wikimedia.org/wikipedia/commons/9/98/Chess_bdt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/bN.svg https://upload.wikimedia.org/wikipedia/commons/e/ef/Chess_ndt45.svg
	curl -s -o public/assets/chess/pieces/cburnett/bP.svg https://upload.wikimedia.org/wikipedia/commons/c/c7/Chess_pdt45.svg



# Generate Composer autoloader
autoload:
	@if command -v composer >/dev/null 2>&1; then \
		echo "📦 Running composer dump-autoload..."; \
		composer dump-autoload; \
	else \
		echo "⚠️  Composer not found. Skipping autoload."; \
	fi

# Optional: Full Composer install
composer-install:
	@if command -v composer >/dev/null 2>&1; then \
		echo "📦 Running composer install..."; \
		composer install; \
	else \
		echo "⚠️  Composer not found. Skipping autoload."; \
	fi

# Remove dependencies and rebuild assets
reset-all: clean-deps npm assets autoload

# Clean node_modules and vendor assets
clean-deps:
	rm -rf vendor node_modules public/vendor

# Stop running container
stop:
	docker stop $(CONTAINER_NAME) || true

# Remove stopped container
clean:
	docker rm -f $(CONTAINER_NAME) || true

# Help menu
help:
	@echo "🧠 Chesskeeper Makefile – Available commands:"
	@echo "  make build         → Build Docker image"
	@echo "  make dev           → Start development container with bind mount"
	@echo "  make prod          → Start production container with persistent volume"
	@echo "  make reset         → Reset environment using bin/reset.php"
	@echo "  make reset-all     → Remove node_modules & vendor and rebuild assets"
	@echo "  make npm           → Install NPM packages"
	@echo "  make assets        → Copy chess.js & chessboard.js into public/vendor"
	@echo "  make autoload      → Generate Composer autoloader"
	@echo "  make composer-install → Install Composer dependencies"
	@echo "  make shell         → Open interactive shell in container"
	@echo "  make stop          → Stop running container"
	@echo "  make clean         → Force remove container"
