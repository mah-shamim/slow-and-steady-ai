$ErrorActionPreference = 'Stop'
Set-Location (Join-Path $PSScriptRoot '..')
if (-not (Get-Command docker -ErrorAction SilentlyContinue)) { throw 'Docker Desktop is required. Install it and enable Docker Compose v2.' }
docker compose version | Out-Null
New-Item -ItemType Directory -Force -Path 'workspace/inbox' | Out-Null
if (-not (Test-Path '.env')) { Copy-Item '.env.example' '.env' }
Write-Host 'Building app container (internet required for first setup)...'
docker compose build
Write-Host 'Starting local services...'
docker compose up -d ollama app
$model = (Get-Content '.env' | Where-Object { $_ -match '^OLLAMA_MODEL=' } | Select-Object -First 1).Split('=')[1]
Write-Host "Downloading model $model (internet required once)..."
docker compose exec ollama ollama pull $model
Write-Host 'Setup complete. Open http://localhost:8080'
Write-Host 'After images and model are cached, disconnect internet and run: docker compose up -d'
