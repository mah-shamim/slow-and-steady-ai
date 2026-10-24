# Testing LazyMate AI

## Unit/API tests

Python 3.12 is recommended. From the repository root:

```bash
python -m venv .venv
# Linux/macOS: source .venv/bin/activate
# Windows PowerShell: .venv\Scripts\Activate.ps1
pip install -r requirements-dev.txt
pytest -q
python -m compileall -q app tests
```

The tests cover the homepage, health response, schema validation, file-plan preview/apply behavior, and folder-boundary enforcement. They do not require a model or internet connection once dependencies are installed.

## Container smoke test

```bash
docker compose config -q
docker compose up -d --build
curl http://localhost:8080/api/health
```

Expected health output has `app: ok`. Before pulling a model, `model_available` can be false. After `docker compose exec ollama ollama pull qwen2.5:3b`, wait for the model to load and submit a short prompt in the UI.

## Offline verification

1. Complete setup and successfully generate a response.
2. Stop the containers: `docker compose down`.
3. Disable network access on the host.
4. Start again: `docker compose up -d`.
5. Check `http://localhost:8080/api/health` and generate another response.

Do not use `docker compose down -v`: it deletes the Ollama model volume.

## File organizer test

Create sample files under `workspace/inbox`, click Preview first, verify each destination, then use Apply. Never test first on valuable files. The test suite uses temporary directories and does not touch user documents.
