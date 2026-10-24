---
title: "SellerMate AI: A Local Bangla Listing Assistant for the Slow and Steady Challenge"
published: false
description: "I built a local-first, open-weight AI assistant that drafts Bangla and English e-commerce product listings without sending product data to a hosted AI API."
tags: opensource, ai, docker, showdev
cover_image: 
---

# SellerMate AI: Let the Sloth Write Your First Product Draft 🦥🇧🇩

Writing product listings is one of those jobs that looks small until you have a whole catalog. A seller may need a title, short description, detailed copy, SEO meta description, keywords, and a social caption for every item. Then they have to do it all over again—in Bangla, English, or both.

I built **SellerMate AI** for the DEV Community’s **“Slow and Steady”** challenge: a local-first assistant that turns verified product facts into a first draft, while keeping the seller in control.

**Project source:** [Add your repository URL here]  
**Demo:** [Add a short screen recording or screenshots here]  
**Agent session / DevRelay:** [Optional: add your saved session link]

> This is a working prototype, not a claim that AI copy is automatically correct. The generated text must be reviewed before publishing.

## The idea: spend less energy on repetitive work

Imagine listing a solar CCTV camera. I enter the name, category, price if known, and the specifications I have verified. SellerMate drafts:

- a product title;
- a short description;
- a longer product description;
- an SEO meta description;
- a social caption; and
- suggested keywords.

I can choose Bangla, English, or bilingual output and copy one field or the complete draft. The goal is not to replace a seller’s judgment. It is to remove the blank-page problem and make the boring first pass easier.

## Why local, open-weight AI?

A hosted AI API would have been a quick way to make a demo. But I wanted the project’s core feature to work without requiring a third-party inference account.

SellerMate uses **Ollama** to run the **Qwen2.5 3B Instruct** model locally. A small FastAPI service validates input and sends the prompt to the local model runtime. The browser interface is served from a Docker container, and the application has no analytics, tracking pixel, external AI API, or required CDN assets.

That choice has practical benefits:

1. **Privacy by architecture.** Product details go to a local service and local model—not a hosted text-generation endpoint. Sellers should still avoid entering customer personal data.
2. **Works offline after setup.** The first run needs internet to download container images and model weights. Once those are present, the app can generate copy without an internet connection.
3. **Model choice remains open.** The configured model can be changed, provided the replacement is downloaded locally and its license and language quality are checked.
4. **Predictable usage costs.** There is no per-token inference bill. The trade-off is that the user supplies the compute, memory, storage, and electricity.
5. **Inspectable behavior.** The prompt, validation rules, API adapter, and UI are in the repository. They can be modified instead of being hidden behind a hosted service.

Open source does not magically make a model accurate or a system secure. Local inference changes who controls the runtime and data path; it does not remove the need for evaluation, safeguards, and human review.

## How it is built

```text
Product form in browser
        |
        v
Local FastAPI service
  - validates fields and length
  - restricts browser origins
  - applies a basic local rate limit
        |
        v
Ollama local runtime
        |
        v
Qwen2.5 3B Instruct
        |
        v
Structured listing fields in the browser
```

The stack is deliberately boring:

- **Docker Compose** to start the services together;
- **PHP 5.6 Apache container** for a simple legacy-compatible web shell (the UI itself is static HTML/CSS/JavaScript);
- **FastAPI and Pydantic** for the local generation endpoint and validation; and
- **Ollama + a local open-weight model** for inference.

PHP 5.6 is end-of-life, so it is isolated and intended only as a compatibility demonstration. I would use a supported PHP release for a new production deployment.

## Guardrails: AI should not invent product facts

Product copy can cause real customer confusion if it invents warranty periods, certifications, battery life, compatibility, or delivery promises. SellerMate’s prompt tells the model to treat input values as data, ignore instructions embedded inside them, and omit unsupported details. The API validates field lengths and accepted language/tone values.

These safeguards reduce risk; they cannot guarantee factual output. A small model can still hallucinate or misunderstand Bangla and English. The seller must check every claim before publishing. The next technical step is a reviewed benchmark of real Bangladeshi product descriptions and a scoring rubric for factuality, language quality, and usefulness.

## Run it locally

Prerequisites: Docker Desktop or Docker Engine with Compose v2, enough disk space for a 3B model, and internet for the initial download.

**Windows PowerShell:**

```powershell
.\scripts\setup.ps1
```

**macOS / Linux:**

```bash
./scripts/setup.sh
```

Then visit **http://localhost:8080**. After setup, disconnect the machine from the internet and try a listing to confirm the local workflow. The model remains in a Docker volume; removing volumes will delete downloaded model data.

The repository includes API tests for health status, input validation, and CORS configuration. They do not need the model weights because they test the service boundaries rather than model quality. The next release should add a repeatable quality evaluation set and resource-use measurements.

## What I learned—and what I would improve

The most important product decision was not the prompt. It was making the local data path explicit and keeping the system small enough for a developer or seller to run themselves.

There are still limitations. A 3B model may need patience on a CPU-only laptop. Bangla output needs careful review. The prototype does not publish directly to WooCommerce or save a catalog. And local inference is only “free” in the narrow sense that there is no hosted inference meter; hardware and power still have costs.

My next steps would be:

- add a catalog import and export-to-WooCommerce draft workflow;
- create a Bangla-specific evaluation set with human-reviewed expected outputs;
- compare output quality and latency across locally runnable models;
- add a human approval queue and reusable store tone settings; and
- publish measured results from low-spec hardware rather than relying on assumptions.

## Why open innovation matters here

For a small seller, control matters. A local model can make the first draft available even when the internet is down, keep product data on a machine they control, and let the developer replace or tune the model as needs change. The trade-off is responsibility: the user must manage hardware, updates, model licensing, and quality checks.

That feels right for “Slow and Steady.” Let the machine do the repetitive first draft, take the time to check the facts, and keep the final decision with the person who knows the product.

**Source code:** [Add repository link]  
**Feedback wanted:** If you sell online in Bangladesh, which part of listing creation eats the most time—Bangla copy, SEO, variants, or publishing to the store?

---

*Model licensing note: check the exact downloaded model card and license before redistribution or commercial use. The project does not bundle model weights.*
