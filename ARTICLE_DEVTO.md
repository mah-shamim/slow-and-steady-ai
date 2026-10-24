---
title: "Two Small Agents, One Local Model: LazyMate + SellerMate for Slow and Steady"
published: false
description: "An offline-first open-source AI suite that handles routine productivity tasks and Bangla e-commerce product copy without sending data to a hosted AI API."
tags: opensource, ai, docker, productivity
---

# Two Small Agents, One Local Model: LazyMate + SellerMate for Slow and Steady 🦥

I like tools that save time without asking me to hand over every note, draft, or business detail to a remote service. For the **Slow and Steady** challenge, I combined two practical assistants into one local-first project: **LazyMate AI** for everyday productivity and **SellerMate AI** for Bangla e-commerce listings.

The goal isn't to make an agent that does everything. It's to make a few tedious tasks easier, keep the human in control, and let the user decide where their data goes.

## What it does

**LazyMate AI** helps with:

- Summarizing long notes into concise takeaways
- Turning rough notes into actionable checklists
- Rewriting drafts for clarity
- Previewing file-organization moves before applying them

**SellerMate AI** turns seller-provided product facts into:

- Product titles and short/full descriptions
- SEO meta descriptions and keywords
- Social captions
- Bangla, English, or bilingual copy

SellerMate is deliberately a drafting assistant, not a source of truth. It should never invent a warranty, certification, specification, or performance claim. The seller must verify every detail before publishing.

## Why open models matter here

Both apps use a shared local [Ollama](https://ollama.com/) runtime and a configurable open-weight model (default: `qwen2.5:3b`). After the Docker images, dependencies, and model weights are downloaded, inference can run without a hosted AI API.

That matters in three ways:

1. **Data stays closer to its owner.** Notes, drafts, and product details can remain on the user's computer rather than being sent to a third-party inference endpoint.
2. **The model is replaceable.** A user can choose a different compatible model after checking its capabilities and license.
3. **Costs are more predictable.** Local inference avoids per-request hosted API charges, although hardware, electricity, storage, and setup time still have costs.

Open-weight doesn't mean every component is automatically open source, perfectly private, or free of restrictions. The model and each dependency have their own terms. Check the exact model card and notices before redistributing weights or using a model commercially.

## Architecture

```text
Browser
  ├── LazyMate UI/API (:8080) ──┐
  └── SellerMate UI (:8081)     ├── Ollama (:11434, internal)
         └── SellerMate API     ┘
```

Docker Compose runs both applications and a single Ollama service. Host ports bind to loopback rather than all network interfaces. Model weights are stored in a Docker volume, not bundled in the source ZIP.

## Run it

Requirements: Docker Desktop or Docker Engine with Compose v2, internet for the first download, and enough disk/RAM for the model.

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec ollama ollama pull qwen2.5:3b
```

Then visit:

- LazyMate: `http://localhost:8080`
- SellerMate: `http://localhost:8081`

On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`. After the images and model are cached, disconnect from the internet and restart the services to test offline operation. See the README for troubleshooting and reproducible test commands.

## Safety is part of the product

Automation should reduce busywork, not create a new cleanup job. LazyMate's file organizer uses a preview-first approach and confines intended operations to a dedicated workspace. SellerMate generates drafts that need human review. The services bind to localhost by default; don't expose them publicly without adding authentication, TLS, and a threat-model review.

Local inference is not a magic privacy shield: malware, compromised hosts, unsafe plugins, and careless file permissions can still expose data. Likewise, a language model can produce incorrect output. Review the result before acting on it.

## Testing and limitations

The repository includes unit/API tests, smoke-test guidance, and CI workflows. Packaging checks are not a substitute for a full deployment test: run the Docker stack on your own machine, verify both interfaces, test file operations with copied samples, and confirm inference while offline. A real model response and browser-level integration should be captured before claiming those checks passed.

The model weights are not included in the ZIP because they are large and have separate terms. The first setup needs internet access. Offline operation begins only after the images and model are present locally.

## What I'd improve next

- Add an explicit local document index with transparent file selection
- Add import/export formats for SellerMate listings
- Build an evaluation set for Bangla fluency and factual consistency
- Add a visible “facts supplied vs. claims generated” review panel
- Publish real demo screenshots and CI results alongside the source

## Why this fits Slow and Steady

A little automation can remove repeated effort without removing choice. LazyMate takes the first pass at notes and file clutter. SellerMate takes the first pass at product copy. Open-weight inference makes both adaptable and usable without a per-request cloud dependency, while preview and review steps keep the person in charge.

**Repository:** _add your public GitHub URL before publishing_

**Demo:** _add a screenshot, short recording, or DevRelay session if available_

Build slowly. Keep it local. Review before you ship. 🦥
