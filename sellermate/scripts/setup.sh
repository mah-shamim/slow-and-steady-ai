#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
command -v docker >/dev/null || { echo "Docker is required. Install Docker Desktop or Docker Engine + Compose plugin."; exit 1; }
docker compose version >/dev/null || { echo "Docker Compose v2 plugin is required."; exit 1; }
if [ ! -f .env ]; then cp .env.example .env; fi
set -a; . ./.env; set +a
echo "Building local web and AI containers..."
docker compose build
# Start Ollama before pulling the model.
docker compose up -d ollama
printf "Waiting for Ollama"
for i in $(seq 1 60); do
  if docker compose exec -T ollama ollama list >/dev/null 2>&1; then break; fi
  printf "."; sleep 2
done
echo
echo "Downloading model ${MODEL_NAME:-qwen2.5:3b}; this first setup requires internet and disk space."
docker compose exec -T ollama ollama pull "${MODEL_NAME:-qwen2.5:3b}"
echo "Starting SellerMate AI..."
docker compose up -d
printf "\nReady: http://localhost:8080\nAPI health: http://127.0.0.1:8000/health\n"
echo "After the model is downloaded, generation can run without internet. Keep the Ollama model volume intact."
