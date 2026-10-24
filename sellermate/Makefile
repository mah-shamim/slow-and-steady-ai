.PHONY: up down logs test health
up:
	docker compose up -d --build

down:
	docker compose down

logs:
	docker compose logs -f --tail=100

test:
	cd ai_service && python -m pytest -q

health:
	curl -fsS http://127.0.0.1:8000/health
