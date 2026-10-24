.PHONY: up down logs test
up:
	docker compose up -d --build
down:
	docker compose down
logs:
	docker compose logs -f --tail=100
test:
	docker compose run --rm --no-deps lazymate pytest -q
	docker compose run --rm --no-deps sellermate-ai pytest -q
