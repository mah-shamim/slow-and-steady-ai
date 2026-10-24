# Security policy

## Supported deployment

This project is a local prototype. The AI API and Ollama ports are bound to loopback by default. Do not publish them to a LAN or the internet as-is. PHP 5.6 is end-of-life and must not be treated as a supported production runtime.

## Data handling

Product inputs are passed to the local AI service and local Ollama runtime. The app does not intentionally send inputs to a remote AI API. Docker image/model downloads contact upstream registries during setup. Do not enter customer personal data or secrets into product fields.

## Reporting a vulnerability

Do not publish sensitive exploit details in a public issue. Contact the repository maintainer privately once a repository URL and contact method have been configured. Until then, treat this as an experimental project and do not expose it to untrusted users.
