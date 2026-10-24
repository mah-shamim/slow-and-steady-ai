import os
os.environ.setdefault("OLLAMA_URL", "http://127.0.0.1:11434")
os.environ.setdefault("MODEL_NAME", "test-model")
from fastapi.testclient import TestClient
from main import app

client = TestClient(app)

def test_health_reports_local_mode():
    response = client.get("/health")
    assert response.status_code == 200
    assert response.json()["mode"] == "local"

def test_rejects_blank_product_name():
    response = client.post("/generate", json={"product_name":"  ", "features":"solar panel"})
    assert response.status_code == 422

def test_rejects_oversized_features():
    response = client.post("/generate", json={"product_name":"Camera", "features":"x" * 3001})
    assert response.status_code == 422

def test_rejects_unsupported_language():
    response = client.post("/generate", json={"product_name":"Camera", "features":"4G", "language":"Martian"})
    assert response.status_code == 422

def test_cors_does_not_allow_arbitrary_origin():
    response = client.options("/generate", headers={"Origin":"https://evil.example", "Access-Control-Request-Method":"POST"})
    assert response.headers.get("access-control-allow-origin") != "https://evil.example"
