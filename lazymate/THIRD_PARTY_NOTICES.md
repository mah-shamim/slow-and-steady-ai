# Third-party components and model licensing

Review the exact versions, licenses, and notices before redistribution or commercial use. This list is informational, not legal advice.

| Component | Use | License / note |
|---|---|---|
| Python | Runtime | Python Software Foundation License; see python.org license files |
| FastAPI | HTTP API | MIT License |
| Uvicorn | ASGI server | BSD-3-Clause |
| HTTPX | HTTP client | BSD-3-Clause |
| Pydantic | Input validation | MIT License |
| Ollama | Local model runtime | MIT License; verify the exact release's notices |
| Qwen2.5 3B Instruct (`qwen2.5:3b`) | Default open-weight model | Qwen2.5 model card lists Apache-2.0; confirm the exact pulled model's card and license before redistribution |
| Docker | Container runtime | Docker Desktop/Engine terms vary by product and use; Docker is not bundled here |

Model weights are downloaded separately and are not included in this source ZIP. Their license and acceptable-use terms are distinct from this application's license. Before swapping models, check the model card, license, commercial-use terms, attribution requirements, and any acceptable-use policy. Do not claim the model is public domain or that every open-weight model has the same license.
