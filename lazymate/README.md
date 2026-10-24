# 🦥 LazyMate AI

**Let the boring work wait on you.** LazyMate is a local-first productivity assistant for summarizing notes, turning text into checklists, rewriting drafts, and organizing a chosen folder with a preview-first workflow. It is built for the DEV Community **Slow and Steady** open-source AI challenge.

- **Local inference:** Ollama + a replaceable open-weight model (default `qwen2.5:3b`).
- **Offline after setup:** once container images, Python dependencies, and model weights are cached, the app can run without internet access.
- **Privacy by default:** no account, hosted AI API key, telemetry, or analytics.
- **Careful automation:** file organizer previews proposed moves and requires explicit confirmation before applying.
- **MIT-licensed app source:** model weights have a separate license; see `THIRD_PARTY_NOTICES.md`.

> Local inference does not guarantee correct output or absolute privacy on a compromised host. Review AI answers and file plans before acting.

## Features

1. Summarize pasted notes or long text into concise bullets.
2. Turn tasks or meeting notes into a prioritized checklist.
3. Rewrite text clearly without intentionally inventing facts.
4. Ask the assistant for practical productivity help.
5. Categorize files in a selected directory using a dry-run plan, then optionally move them.
6. Use English, Bangla, or bilingual response preferences (quality depends on the selected model).

## Quick start

### Requirements

- Docker Desktop (Windows/macOS) or Docker Engine on Linux
- Docker Compose v2
- Internet for first-time image/model downloads
- Disk space for Docker images and the model; `qwen2.5:3b` is several GB depending on quantization/runtime

### Windows PowerShell

1. Extract the ZIP and open PowerShell in the `lazymate-ai` folder.
2. Run:

   ```powershell
   .\scripts\setup.ps1
   ```

3. Open **http://localhost:8080**.

### Linux / macOS

```bash
chmod +x scripts/setup.sh
./scripts/setup.sh
```

Then open **http://localhost:8080**.

Setup builds the app container, starts Ollama, and downloads the model configured in `.env`. The model is stored in a named Docker volume, not in the source ZIP.

### Manual setup

```bash
cp .env.example .env
docker compose build
docker compose up -d ollama app
docker compose exec ollama ollama pull qwen2.5:3b
```

If you change `OLLAMA_MODEL` in `.env`, pull that exact model name. Restart with `docker compose up -d --force-recreate app` if you changed the app environment.

## Work offline

1. Finish setup while online and verify that a prompt generates a response.
2. Stop the stack: `docker compose down`.
3. Disable network access on your computer.
4. Start again: `docker compose up -d`.
5. Open the app and test a prompt.

Docker itself, the app image, Ollama, and the model must already be cached. Avoid `docker compose down -v`, which deletes the model volume. No application functionality requires an external AI endpoint; first-time setup still needs registries to download components.

## File organizer

The container only mounts the project's `./workspace` folder at `/workspace`. On Linux/macOS, the setup script makes the dedicated workspace directories writable by the unprivileged container user; keep sensitive files out of this folder. Create a test folder at `workspace/inbox`, put copies of a few files there, and use **Preview organization**. The plan sorts common file types into folders such as Documents, Images, Spreadsheets, Audio, Video, Archives, Code, and Other. Only click **Apply plan** after reviewing the proposed moves. The app does not delete files. Avoid mounting your home directory or sensitive folders.

## Configuration

| Variable | Default | Meaning |
|---|---|---|
| `OLLAMA_MODEL` | `qwen2.5:3b` | Model tag already pulled into Ollama |
| `MAX_TEXT_CHARS` | `30000` | Maximum pasted text length |
| `RATE_LIMIT_REQUESTS` | `30` | Requests per IP within the rate window |
| `RATE_LIMIT_WINDOW_SECONDS` | `60` | Rate limit window |

The UI binds to `127.0.0.1:8080`. Ollama is only exposed inside the Docker network. Do not expose the app to a LAN or public internet without authentication, TLS, CSRF protection, and a security review.

## API

- `GET /api/health` — app/model availability status
- `POST /api/assist` — body: `{"text":"...","mode":"summarize","language":"English"}`. Modes: `help`, `summarize`, `checklist`, `rewrite`.
- `POST /api/file-plan` — body: `{"root":"/workspace/inbox","apply":false}`. Preview by default; set `apply` to `true` only after review.
- `GET /api/docs` — local OpenAPI docs

## Tests

```bash
python -m venv .venv
# Linux/macOS: source .venv/bin/activate
# Windows PowerShell: .venv\Scripts\Activate.ps1
pip install -r requirements-dev.txt
pytest -q
python -m compileall -q app tests
docker compose config -q
```

See [`docs/TESTING.md`](docs/TESTING.md) for container smoke tests and a repeatable offline test. CI runs pytest, Python compilation, and Compose configuration validation. Real-model generation requires a downloaded model and is deliberately not part of the unit-test suite.

## Architecture

```text
Browser (localhost:8080)
      │
      ▼
FastAPI app container ───── private Compose network ───── Ollama
      │                                                     │
      └── /workspace bind mount (user-selected files)       └── model volume
```

The web app is a small HTML/CSS/JavaScript frontend, FastAPI backend, and Ollama inference service. Model provider and model tag are replaceable without changing the UI. AI output is advisory; file operations are deterministic rules and require user approval.

## Security and privacy

Read [`SECURITY.md`](SECURITY.md). Key points: local-only published port, non-root app user, read-only app filesystem, dropped capabilities, input limits, basic in-memory rate limiting, constrained workspace mount, no telemetry, and preview-first file moves. The rate limiter is intended as a basic safeguard, not a production security boundary.

## Licensing

The application source is MIT licensed. Python packages, Ollama, and model weights have their own licenses. The default Qwen2.5 model card identifies Apache-2.0 licensing, but check the exact model card and license before redistribution or commercial use. See [`THIRD_PARTY_NOTICES.md`](THIRD_PARTY_NOTICES.md).

## Roadmap

- Optional local Whisper transcription for voice notes
- Background scheduled summaries with explicit folder selection
- Undo manifest for file moves
- Local document search with SQLite/FTS or an optional vector index
- More language-specific evaluation cases and benchmark prompts

## Challenge story

LazyMate embodies **Slow and Steady** by turning repeated, low-value work into calm, reviewable steps. Open-weight local inference keeps notes on the user's machine, allows model swaps, and avoids per-request hosted AI fees. The trade-off is real: users provide their own compute, model quality varies, and setup takes a little effort. That is the point—set it up once, then let the small tasks get out of the way.
