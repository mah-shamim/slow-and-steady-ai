# Security overview

This repository contains two local-first applications. The defaults reduce exposure but are not a substitute for host security or a production security review.

- Host ports should bind to `127.0.0.1`; do not expose them publicly.
- Do not store secrets in source control. `.env` is ignored by Git.
- Use a dedicated, non-sensitive test workspace for file-organization tests.
- Review every proposed file move and every generated product claim before applying/publishing.
- Keep Docker and host OS updated. Offline mode does not protect a compromised host.
- Do not upload confidential files into a machine shared with untrusted users.
- If exposing on a LAN, add authentication, TLS, authorization, request limits, and a threat-model review first.

Report suspected vulnerabilities privately to the repository maintainer once a public repository is created. Avoid posting exploit details in a public issue before a fix is available.
