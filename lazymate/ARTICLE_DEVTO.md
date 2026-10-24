---
title: "LazyMate AI: Let the Boring Work Wait on You (Offline, Open-Weight, Local-First)"
published: false
description: "A local productivity assistant that summarizes notes, creates checklists, and tidies a chosen folder using open-weight AI. Built for the Slow and Steady challenge."
tags: opensource, ai, productivity, docker
---

# 🦥 LazyMate AI: Let the Boring Work Wait on You

I have a small confession: I don't want another productivity dashboard to maintain. I want fewer tiny tasks competing for my attention.

Summarize these notes. Turn this rambling message into a checklist. Make this paragraph clearer. Help me sort the folder I have been avoiding for three weeks.

Each task is small. Together, they steal the quiet parts of a day.

For the **Slow and Steady** open-source AI challenge, I built **LazyMate AI**: a local-first assistant that handles some of that busywork without sending pasted notes to a hosted AI API.

> **The idea:** do the setup once, keep the model on your machine, and let the small tasks get out of the way.

## What it does

LazyMate currently has two small work areas:

- **Think less:** summarize text, produce a checklist, rewrite a draft, or ask for practical productivity help.
- **Tidy gently:** preview a file-organization plan for a chosen workspace folder, then explicitly approve the moves.

It has a simple browser interface and a small API. The file organizer uses deterministic extension rules; the language assistant uses a replaceable local model. That split is intentional: an LLM should not be deciding whether it has permission to move someone's files.

## Why open-weight AI?

A hosted model would make the first demo easier, but it would also make the app depend on a network, a vendor account, API availability, and potentially per-request charges. For a tool that handles personal notes, those trade-offs matter.

LazyMate uses **Ollama** to run the default **Qwen2.5 3B Instruct** model locally. After Docker images and model weights have been downloaded, the app can be used offline. The model is replaceable, so users can test alternatives and choose the balance of speed, language quality, and hardware requirements that works for them.

Open innovation is not just a cost story. It means the inference path is inspectable and adaptable. A user can change the model, audit the application, fork the workflow, or keep the whole setup on a computer they control. There is no mandatory hosted AI key in this project.

There are honest trade-offs: the user's computer does the work, a small model can miss nuance, and first-time setup needs internet access to fetch images and model weights. Local inference is not magic privacy armor if the host machine itself is compromised.

## How it is built

```text
Browser on localhost
        │
        ▼
FastAPI application ─── private Docker network ─── Ollama
        │                                         │
        └── selected /workspace folder             └── local model volume
```

The stack is deliberately boring:

- Python 3.12 + FastAPI for the API
- HTML, CSS, and plain JavaScript for the UI
- Ollama for local inference
- Docker Compose for repeatable setup
- pytest for API and file-operation tests

No account system, analytics, telemetry, vector database, or cloud AI provider is required for the current features. I chose not to add more infrastructure than the problem needs.

## A small safety rule: preview before action

File automation is where a cute demo can become a bad day. LazyMate only sees the project's mounted `workspace` directory. It skips symlinks, creates a proposed plan first, and requires a separate Apply action. It does not delete files.

The app also binds to localhost, keeps Ollama off the host's published ports, runs the application as a non-root user, drops Linux capabilities, and limits request size. These controls are not a substitute for a security audit, but they make the safe path the default.

The model can still hallucinate or be influenced by malicious text. That's why generated summaries and checklists are suggestions, not unquestionable truth. The user remains in charge.

## Run it locally

Requirements: Docker Desktop or Docker Engine with Compose v2, plus enough disk space for the model.

**Windows PowerShell:**

```powershell
.\scripts\setup.ps1
```

**Linux/macOS:**

```bash
chmod +x scripts/setup.sh
./scripts/setup.sh
```

Open `http://localhost:8080`. The setup script builds the app, starts Ollama, and downloads the configured model. Once the images and weights are cached, disconnect from the internet and start the stack again to verify offline use.

The source archive includes a README, security notes, third-party license notes, API tests, and a GitHub Actions workflow. Run the unit tests with:

```bash
pip install -r requirements-dev.txt
pytest -q
```

The unit tests don't require model weights. A separate manual smoke test verifies actual inference with the model running.

## What I learned / what I would improve

The most useful design decision was keeping file movement separate from the model. AI is good at turning messy text into useful structure; predictable code is better at enforcing the boundaries around file access.

Next, I'd add an undo manifest, optional local voice transcription, scheduled summaries for explicitly selected folders, and language-specific evaluation cases. I would also measure output quality and latency on modest hardware rather than assuming that “runs locally” automatically means “runs well.”

## Why this fits Slow and Steady

LazyMate isn't trying to automate a person's whole life. It takes a handful of repetitive chores off the list, one small step at a time. The open stack gives users control over where inference happens and which model they trust. After setup, the assistant can keep working without an internet connection or a per-request bill.

That feels like a sloth-approved kind of productivity: move slowly, conserve attention, and don't make the user do the same boring thing twice.

## Try it / inspect it

- **Source repository:** [ADD YOUR PUBLIC REPOSITORY URL]
- **Demo screenshot or recording:** [ADD A REAL SCREENSHOT / VIDEO]
- **DevRelay session (optional):** [ADD SESSION LINK IF AVAILABLE]

The project is an early prototype, not a production-ready multi-user service. I'd love feedback on the best offline model for Bangla and English notes, and on which small chores are worth automating next.

---

### License note

The application source is MIT licensed. The default Qwen2.5 model has a separate model license (identified as Apache-2.0 on its model card); check the exact model card and all dependency notices before redistributing model weights or using a different model commercially.
