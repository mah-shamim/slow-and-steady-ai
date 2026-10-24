.PHONY: test lint compose-check

test:
	pytest -q

lint:
	python -m compileall -q app tests

compose-check:
	docker compose config -q
