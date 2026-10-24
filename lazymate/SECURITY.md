# Security notes

- Intended for a single trusted user on a local machine, not as a public multi-user service.
- The web port binds to `127.0.0.1` only. Do not change this to `0.0.0.0` without adding authentication, TLS, CSRF protections, and a threat review.
- Ollama's API is exposed only to the Compose network, not to the host.
- The app runs as a non-root user, with a read-only root filesystem, dropped Linux capabilities, and `no-new-privileges` in Compose. The setup script makes only the dedicated `workspace` directories writable so the container user can move test files; keep sensitive files out of that folder.
- User text is capped at 30,000 characters; API requests have a basic in-memory rate limit. This limiter resets on restart and is not a substitute for a production gateway.
- File operations are limited to the mounted `./workspace` directory, require an explicit allowed root, skip symlinks, preview by default, and require a separate Apply action.
- Back up files before applying an organization plan. The app does not delete files, but moves can still disrupt workflows.
- Prompt injection is possible in model input. The system prompt asks the model to treat pasted content as data, but this is not a security boundary. Do not feed secrets or rely on generated output without review.
- No telemetry, account, cloud AI API, or analytics is implemented. Docker image/package/model downloads do contact their respective registries during setup.
- Keep Docker, host OS, and dependencies patched. Avoid mounting your home directory or sensitive folders into the container.
