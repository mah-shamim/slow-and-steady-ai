# SellerMate AI 🇧🇩

**A local-first Bangla e-commerce product listing assistant built for the DEV Community “Slow and Steady” challenge.**

SellerMate turns verified product facts into a first draft of a product title, short and full descriptions, SEO meta description, social caption, and keywords. It runs an open-weight model locally through Ollama. Once Docker images and the model are downloaded, generation does not require an internet connection or a paid hosted AI API.

> **Important:** Generated text is a draft, not a source of truth. Review every specification, claim, price, warranty, and offer before publishing.

## Features

- Bangla, English, or bilingual listing copy
- Product title, short description, full description, meta description, social caption, and SEO keywords
- Local model inference through Ollama; no third-party AI API key
- Small browser UI with per-field and copy-all actions
- Input length/type validation, restricted CORS origins, loopback-bound API ports, basic request rate limiting
- Docker Compose setup for Windows PowerShell, macOS, and Linux
- API validation tests and GitHub Actions workflow
- No database, analytics, tracking pixel, or external browser CDN

## Architecture

```text
Browser (localhost:8080)
   ├── static HTML/CSS/JS + PHP 5.6 Apache container
   └── POST product facts ──> local FastAPI service (127.0.0.1:8000)
                                  └──> Ollama (127.0.0.1:11434)
                                           └── local model volume
```

The UI is static and does not need PHP features; PHP 5.6 is included as a legacy-compatible web container because this project is intended to be easy to adapt to older PHP shops. **PHP 5.6 is end-of-life.** Do not expose this container directly to the public internet. For a new production system, use a supported PHP version and add authentication, HTTPS, and stronger abuse controls.

## Requirements

- Docker Desktop (Windows/macOS) or Docker Engine + Docker Compose v2 (Linux)
- 8 GB RAM recommended for the default 3B model; more memory improves comfort
- Several GB of free disk space for images and model weights
- Internet for the initial image/model download only

## Quick start

### Windows

1. Install and start Docker Desktop.
2. Extract this ZIP to a writable folder.
3. Open PowerShell in the `sellermate-ai` folder.
4. Run:

   ```powershell
   .\scripts\setup.ps1
   ```

5. Open **http://localhost:8080**.

### macOS / Linux

```bash
chmod +x scripts/setup.sh
./scripts/setup.sh
```

Then open **http://localhost:8080**.

The setup script builds the containers, starts Ollama, downloads the model configured in `.env`, and starts the application. The first run needs internet. After that, disconnect the machine from the internet and generate a test listing to verify your local setup is self-contained.

## Manual setup

```bash
cp .env.example .env
# Optional: edit MODEL_NAME in .env
docker compose build
docker compose up -d ollama
docker compose exec ollama ollama pull qwen2.5:3b
docker compose up -d
```

If you change `MODEL_NAME`, make sure the same model has been pulled into the Ollama volume. To use a different model, check its language quality, hardware requirements, and license first.

## Use it

1. Enter a product name and verified features.
2. Optionally enter category, price, and search keywords.
3. Select Bangla, English, or both, plus a tone.
4. Select **Generate my listing** and wait for local inference.
5. Copy individual fields or all fields, then fact-check before publishing.

Do not put customer names, phone numbers, addresses, order details, passwords, or other sensitive personal information into product fields.

## Operations

```bash
docker compose ps                 # service status
docker compose logs -f ai          # AI service logs
docker compose logs -f ollama     # model runtime logs
curl http://127.0.0.1:8000/health # local API health

docker compose down               # stop services; model volume remains
# Destructive: removes downloaded model data too
# docker compose down -v
```

### Run tests

The tests cover health response, validation boundaries, and CORS configuration. They do not call a real model, so they can run without model weights or internet once Python dependencies are installed.

```bash
python -m venv .venv
# macOS/Linux: source .venv/bin/activate
# Windows PowerShell: .\.venv\Scripts\Activate.ps1
pip install -r ai_service/requirements-dev.txt
cd ai_service
python -m pytest -q
```

Expected: `5 passed`. If your environment has a newer incompatible package set, use the pinned requirements files included in this project.

## API

`GET http://127.0.0.1:8000/health` returns local mode and configured model name.

`POST http://127.0.0.1:8000/generate` accepts JSON:

```json
{
  "product_name": "4G Solar Security Camera",
  "category": "CCTV camera",
  "price": "৳ 2,500",
  "features": "4G SIM, solar panel, two lenses",
  "keywords": "4G camera, solar CCTV",
  "language": "Bangla",
  "tone": "clear and helpful"
}
```

Allowed language values: `Bangla`, `English`, `Both`. Allowed tone values: `clear and helpful`, `friendly and persuasive`, `professional`, `concise`. Field length limits are enforced by the API.

## Security and privacy notes

- API and Ollama ports bind to loopback (`127.0.0.1`) on the host; do not change to `0.0.0.0` casually.
- CORS allows only the two localhost origins configured by default. CORS is not authentication; loopback binding is also important.
- Input fields have size limits and the API applies a small in-memory per-client request limit.
- Model prompts explicitly treat product values as data and prohibit unsupported claims. Prompt instructions are not a perfect security boundary; human review remains necessary.
- No external analytics or remote inference is used by the application code. Docker must contact registries and Ollama model hosting during first-time setup.
- If the machine is shared, anyone with local access may be able to use the app or inspect local model files. Use OS account controls and disk encryption as appropriate.
- Do not expose the service to a LAN/public internet without authentication, HTTPS, a proper reverse proxy, persistent rate limiting, and security review.

## Open model licensing

The default Ollama model tag is `qwen2.5:3b` (Qwen2.5-3B-Instruct). The Qwen2.5 3B model family is published under Apache License 2.0 according to its model card, but **verify the exact model card and license for the artifact you download before redistributing weights or using it commercially**. The Ollama runtime and Docker base images have their own licenses and notices. This repository does not bundle model weights; setup downloads them separately. Keep upstream notices and comply with the license of every component you deploy.

## Limitations / next steps

- Output quality depends on the selected model, hardware, prompt, and supplied facts.
- This prototype does not save products, publish directly to WooCommerce, scrape supplier pages, or validate claims against a catalog.
- Tests mock no model calls and do not measure Bengali writing quality. Add a reviewed Bangla benchmark set before production.
- Suggested improvements: local catalog import, WooCommerce draft export, editable prompt templates, model selector UI, offline language-quality evaluation, and a human approval queue.

## Repository layout

```text
app/                 Offline browser UI (PHP/HTML/CSS/JS)
ai_service/           FastAPI validation and local Ollama adapter
scripts/              Windows and Unix setup scripts
tests/                Lightweight repository smoke checks
.github/workflows/    CI checks
Dockerfile / compose  Local deployment
ARTICLE_DEVTO.md      Polished challenge submission draft
```

## License

Project source code: MIT. This license does not change the separate licenses of the model, Ollama, Python packages, or container base images. See `LICENSE` and `THIRD_PARTY_NOTICES.md`.
