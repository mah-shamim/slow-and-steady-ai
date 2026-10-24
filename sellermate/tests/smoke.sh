#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
python3 - <<'PY' "$ROOT"
import pathlib, sys
root = pathlib.Path(sys.argv[1])
required = ['docker-compose.yml','README.md','ARTICLE_DEVTO.md','app/index.php','app/app.js','ai_service/main.py','scripts/setup.sh','scripts/setup.ps1']
missing = [name for name in required if not (root / name).is_file()]
if missing:
    raise SystemExit('Missing required files: ' + ', '.join(missing))
# The UI should not rely on an external CDN for offline use.
html = (root / 'app/index.php').read_text(encoding='utf-8')
if 'https://' in html or 'http://' in html:
    raise SystemExit('Unexpected external URL in UI HTML; keep the UI offline-capable.')
print('Repository smoke checks passed.')
PY
