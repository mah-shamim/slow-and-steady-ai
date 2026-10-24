from pathlib import Path
from fastapi.testclient import TestClient
import app.main as main

client = TestClient(main.app)

def test_home_page():
    response = client.get('/')
    assert response.status_code == 200
    assert 'LazyMate AI' in response.text

def test_health_does_not_require_model(monkeypatch):
    async def unavailable():
        return {"app": "ok", "model": "qwen2.5:3b", "ollama": "unavailable", "model_available": False}
    monkeypatch.setattr(main, 'health', unavailable)
    response = client.get('/api/health')
    assert response.status_code == 200
    assert response.json()['app'] == 'ok'

def test_assist_rejects_invalid_mode():
    response = client.post('/api/assist', json={"text":"hello", "mode":"delete_everything"})
    assert response.status_code == 422

def test_file_plan_blocks_outside_workspace(tmp_path, monkeypatch):
    root = tmp_path / 'outside'
    root.mkdir()
    (root / 'a.txt').write_text('x')
    monkeypatch.setattr(main, 'WORKSPACE_ROOT', (tmp_path / 'safe').resolve())
    main.WORKSPACE_ROOT.mkdir()
    response = client.post('/api/file-plan', json={"root":str(root)})
    assert response.status_code == 403


def test_file_plan_preview_and_apply(tmp_path, monkeypatch):
    root = tmp_path / 'workspace' / 'inbox'
    root.mkdir(parents=True)
    (root / 'notes.txt').write_text('hello')
    monkeypatch.setattr(main, 'WORKSPACE_ROOT', (tmp_path / 'workspace').resolve())
    preview = client.post('/api/file-plan', json={"root":str(root)})
    assert preview.status_code == 200
    assert preview.json()['count'] == 1
    assert (root / 'notes.txt').exists()
    applied = client.post('/api/file-plan', json={"root":str(root), "apply":True})
    assert applied.status_code == 200
    assert (root / 'Documents' / 'notes.txt').exists()
    assert not (root / 'notes.txt').exists()

def test_file_plan_rejects_parent_path(tmp_path, monkeypatch):
    safe = tmp_path / 'workspace'
    safe.mkdir()
    outside = tmp_path / 'outside'
    outside.mkdir()
    (outside / 'a.txt').write_text('x')
    monkeypatch.setattr(main, 'WORKSPACE_ROOT', safe.resolve())
    response = client.post('/api/file-plan', json={"root":str(outside)})
    assert response.status_code == 403
