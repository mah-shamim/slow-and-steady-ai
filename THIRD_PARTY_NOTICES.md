# Third-party notices

The suite combines independently licensed components. Review the exact version and license files for every component before redistribution.

- Application source in each subproject: MIT License (see each `LICENSE`).
- Ollama: separate project and license; see https://github.com/ollama/ollama.
- Default model: `qwen2.5:3b`, published by Qwen. The model card has listed Apache-2.0 terms for this model family, but verify the exact model tag and its current model card before redistribution or commercial use: https://ollama.com/library/qwen2.5 and https://huggingface.co/Qwen/Qwen2.5-3B-Instruct.
- Python packages, PHP runtime/base images, and Docker images retain their own licenses and notices. Inspect dependency metadata and upstream notices for the versions actually installed.

No model weights are bundled in this source archive. The user downloads them separately during setup. “Open-weight” does not mean that all system components share one license or that outputs are guaranteed correct.
