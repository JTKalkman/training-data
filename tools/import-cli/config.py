from __future__ import annotations

import os
from dataclasses import dataclass
from pathlib import Path


@dataclass(frozen=True)
class Config:
    base_url: str
    token: str


def load_config() -> Config:
    _load_dotenv_if_present()

    base_url = os.environ.get("TRAININGSDATA_BASE_URL")
    token = os.environ.get("TRAININGSDATA_TOKEN")

    missing = [
        name for name, value in [("TRAININGSDATA_BASE_URL", base_url), ("TRAININGSDATA_TOKEN", token)]
        if not value
    ]
    if missing:
        raise SystemExit(
            f"Missing required config: {', '.join(missing)}\n"
            f"Set them as environment variables, or in a .env file next to main.py."
        )

    return Config(base_url=base_url, token=token)


def _load_dotenv_if_present() -> None:
    """Minimal .env loader, no external dependency needed for just KEY=VALUE lines."""
    env_path = Path(__file__).parent / ".env"
    if not env_path.exists():
        return

    for line in env_path.read_text().splitlines():
        line = line.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, _, value = line.partition("=")
        os.environ.setdefault(key.strip(), value.strip())
