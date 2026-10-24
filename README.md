# Slow & Steady AI Suite 🦥

Two local-first open-source AI assistants in one Docker Compose project:

- **LazyMate AI** — summarize notes, turn text into checklists, rewrite drafts, and safely preview file organization.
- **SellerMate AI** — generate Bangla, English, or bilingual e-commerce product titles, descriptions, SEO metadata, and social captions.

Both use one shared local Ollama runtime. No hosted AI API or account is required. Once container images, dependencies, and model weights have been downloaded, normal inference is designed to work offline.

## Quick start

Requirements: Docker Desktop or Docker Engine, Docker Compose v2, internet for initial downloads, and enough disk/RAM for `qwen2.5:3b` (model weights are several GB).

### Windows PowerShell

```powershell
Copy-Item .env.example .env
# Optional: edit .env to choose a model

docker compose up -d --build
# Download model once (first setup only)
docker compose exec ollama ollama pull qwen2.5:3b
```

### Linux/macOS

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec ollama ollama pull qwen2.5:3b
```

Open the unified application at **http://localhost:8080**. Use the navigation bar to switch between LazyMate and SellerMate; SellerMate is also available at http://localhost:8080/sellermate/.

All browser traffic uses this single loopback address. The AI services and individual app containers are only reachable inside the Compose network.

Check status and logs:

```bash
docker compose ps
docker compose logs --tail=100
```

Stop the suite with `docker compose down`. Model weights remain in the `ollama_data` volume. Do **not** run `docker compose down -v` unless you intend to delete the model volume.

## Offline verification

1. Complete the initial image and model downloads while online.
2. Confirm both apps work and generate one output each.
3. Disconnect the host from the internet.
4. Run `docker compose up -d` and test both apps again. Docker may need cached images; do not use `--pull` or `--build` during this check.
5. Note: OS, Docker, or model updates require internet. Offline capability does not mean model weights are bundled in this ZIP.

## Reproducible tests

Run the API/unit tests with Python 3.12 (recommended inside separate virtual environments):

```bash
cd lazymate
python -m venv .venv
# Activate .venv, then:
python -m pip install -r requirements-dev.txt
python -m pytest -q
cd ../sellermate/ai_service
python -m venv .venv
# Activate this .venv, then:
python -m pip install -r requirements-dev.txt
python -m pytest -q
```

The two suites currently contain 6 LazyMate tests and 5 SellerMate API tests. Tests that need a live Ollama model should be run separately against the running stack.

Shell smoke test:

```bash
bash sellermate/tests/smoke.sh
```

CI workflows are included in each subproject. Docker runtime, live model inference, and cross-browser behavior should also be tested on your target machine; those are not claimed as verified by packaging alone.

## Project layout

```text
slow-and-steady-ai/
├── docker-compose.yml       # shared Ollama + both apps
├── .env.example
├── README.md
├── ARTICLE_DEVTO.md         # polished challenge submission
├── lazymate/                # FastAPI app, UI, file organizer, tests
└── sellermate/              # PHP UI, FastAPI AI service, tests
```

## Security notes

- Services bind to loopback (`127.0.0.1`) instead of all network interfaces.
- Do not expose these ports publicly or forward them through a router.
- LazyMate file operations are intended to remain inside its mounted `lazymate/workspace` folder. Use copied test files and review the move preview before applying changes.
- Treat model output as untrusted: verify facts, product claims, prices, and generated copy before publishing.
- Keep sensitive documents out of shared or untrusted machines. Local processing reduces third-party transmission but cannot protect against a compromised host.
- Review `lazymate/SECURITY.md` and `sellermate/SECURITY.md` before deployment.

## Licensing

The app source is MIT-licensed in each subproject. The model is a separate artifact with separate terms; the default `qwen2.5:3b` model is generally published under Apache-2.0, but verify the exact model card/version before redistribution or commercial use. See both `THIRD_PARTY_NOTICES.md` files. Dependencies and Ollama have their own licenses.

## Troubleshooting

- Model missing: `docker compose exec ollama ollama pull qwen2.5:3b`.
- Check model list: `docker compose exec ollama ollama list`.
- LazyMate logs: `docker compose logs -f lazymate`.
- SellerMate logs: `docker compose logs -f sellermate-ai sellermate-web`.
- Port in use: change the host-side port mapping in `docker-compose.yml`.
- For fully air-gapped deployment, pre-load Docker images and the Ollama model on a connected machine, then transfer them according to your organization's security policy.

## Challenge framing

“Slow and Steady” means spending less attention on repetitive work without surrendering control. LazyMate handles routine notes and file organization with a preview-first workflow; SellerMate drafts product copy from seller-provided facts. Open weights let users run locally and replace the model, while review steps keep automation accountable.
