# Test plan and reproducibility

## Automated checks included

1. API health reports local mode.
2. Blank required product name is rejected.
3. Oversized feature input is rejected.
4. Unsupported language is rejected.
5. Arbitrary browser origin is not granted CORS access.
6. Repository smoke test checks required project files and rejects external URLs in the UI HTML.

Run:

```bash
pip install -r ai_service/requirements-dev.txt
python -m pytest -q ai_service/test_main.py
bash tests/smoke.sh
```

Expected current result: **5 API tests passed** and repository smoke checks passed.

## Manual acceptance test

- [ ] `docker compose config` succeeds.
- [ ] `http://localhost:8080` loads with internet disconnected after initial setup.
- [ ] `http://127.0.0.1:8000/health` returns `status: ok`.
- [ ] A Bangla listing is generated from verified facts.
- [ ] English and bilingual output modes work.
- [ ] Missing required fields and oversized fields are rejected.
- [ ] The generated content can be copied.
- [ ] Stop and restart containers while offline; confirm the model volume persists.
- [ ] Review model output for unsupported claims and Bangla quality.

## Environment limitation

Docker is not installed in the build environment used to prepare this ZIP, so image builds and end-to-end inference were not executed here. CI covers service validation and repository smoke checks; run the manual acceptance tests on a machine with Docker before relying on this in a live store.
