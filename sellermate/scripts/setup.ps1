$ErrorActionPreference = 'Stop'
Set-Location (Join-Path $PSScriptRoot '..')
if (-not (Get-Command docker -ErrorAction SilentlyContinue)) { throw 'Docker Desktop is required.' }
docker compose version | Out-Null
if (-not (Test-Path '.env')) { Copy-Item '.env.example' '.env' }
$model = 'qwen2.5:3b'
$envLine = Get-Content '.env' | Where-Object { $_ -match '^MODEL_NAME=' } | Select-Object -First 1
if ($envLine) { $model = $envLine.Split('=',2)[1].Trim() }
Write-Host 'Building local web and AI containers...'
docker compose build
docker compose up -d ollama
Write-Host "Downloading model $model; internet is required only for this initial step."
docker compose exec -T ollama ollama pull $model
docker compose up -d
Write-Host 'Ready: http://localhost:8080'
Write-Host 'API health: http://127.0.0.1:8000/health'
Write-Host 'After setup, generation runs locally without internet. Keep the Ollama model volume intact.'
