# Combined suite test guide

## Unit/API tests

Use Python 3.12 and separate virtual environments for each Python service.

```bash
cd lazymate
python -m venv .venv
# Activate the environment, then:
python -m pip install -r requirements-dev.txt
python -m pytest -q

cd ../sellermate/ai_service
python -m venv .venv
# Activate the environment, then:
python -m pip install -r requirements-dev.txt
python -m pytest -q
```

Current packaged test baseline: LazyMate 6 tests; SellerMate 5 tests. These tests use mocked/local API behavior and do not prove the model is installed or that a live model generates high-quality Bangla.

## Integration checklist

1. `docker compose up -d --build`
2. `docker compose exec ollama ollama pull qwen2.5:3b`
3. Open `http://localhost:8080`, switch between LazyMate and SellerMate from the shared navigation, and verify both pages load.
4. Verify SellerMate generation sends its request through `/sellermate-api/generate` on the same origin.
5. Test invalid/oversized inputs and inspect logs.
6. For file organization, create harmless sample files inside `lazymate/workspace/inbox`; preview the proposed plan before applying.
7. Test offline only after image and model downloads are complete.

Never use important files for the first file-organizer test. Review generated copy and verify all claims before publishing.
