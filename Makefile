# Basis
IMAGE_NAME = chesskeeper
CONTAINER_NAME = chesskeeper
PORT = 8080

# Docker build
build:
	docker build -t $(IMAGE_NAME) .

# Entwicklung (live bind mount)
dev:
	docker run -d --rm \
		-v "$$(pwd)":/app \
		-p $(PORT):8080 \
		--name $(CONTAINER_NAME) \
		$(IMAGE_NAME)

# Reset mit Umgebungsvariablen (explizit)
reset:
	docker run -it --rm \
		-v "$$(pwd)/data":/app/data \
		-w /app \
		-e CK_USERNAME="$(CK_USERNAME)" \
		-e CK_PASSWORD="$(CK_PASSWORD)" \
		-e CK_PLAYER_NAME="$(CK_PLAYER_NAME)" \
		$(IMAGE_NAME) \
		php bin/reset.php

# Prod-Modus (Image nutzt App-Code aus Build)
prod:
	docker run -d \
		-v "$$(pwd)/data":/app/data \
		-p $(PORT):8080 \
		--name $(CONTAINER_NAME) \
		$(IMAGE_NAME)

# Interaktive Shell im Container (live dev code)
shell:
	docker run -it --rm \
		-v "$$(pwd)":/app \
		-w /app \
		--entrypoint sh \
		$(IMAGE_NAME)

# Stop container
stop:
	docker stop $(CONTAINER_NAME) || true

# Cleanup stopped container (optional)
clean:
	docker rm -f $(CONTAINER_NAME) || true

# Hilfe
help:
	@echo "🧠 Chesskeeper Makefile – verfügbare Befehle:"
	@echo "  make build     → baue Docker-Image"
	@echo "  make dev       → starte Entwicklung mit Bind Mount"
	@echo "  make prod      → starte Produktion mit persistentem data/"
	@echo "  make reset     → führe Reset per bin/reset.php aus"
	@echo "  make shell     → öffne Shell im Container"
	@echo "  make clean     → entferne Container (falls vorhanden)"
