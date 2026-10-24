#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
command -v docker >/dev/null || { echo 'Docker is required. Install Docker Engine and Compose v2 first.'; exit 1; }
docker compose version >/dev/null
mkdir -p workspace/inbox
# The app runs as an unprivileged container user; grant write access only to this dedicated workspace.
chmod 0777 workspace workspace/inbox
cp -n .env.example .env 2>/dev/null || true
echo 'Building app container (internet required for first setup)...'
docker compose build
echo 'Starting local services...'
docker compose up -d ollama app
echo 'Downloading model qwen2.5:3b (internet required once)...'
docker compose exec ollama ollama pull "$(grep '^OLLAMA_MODEL=' .env | cut -d= -f2)"
echo
echo 'Setup complete. Open http://localhost:8080'
echo 'After images and model are cached, you can disconnect the internet and run: docker compose up -d'
