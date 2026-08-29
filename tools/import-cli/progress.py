from __future__ import annotations

import sys
import time


class Progress:
    def __init__(self, total: int):
        self.total = total
        self.done = 0
        self.success = 0
        self.duplicate = 0
        self.invalid = 0
        self.failed = 0
        self._started_at = time.monotonic()

    def update(self, status: str) -> None:
        self.done += 1
        if hasattr(self, status):
            setattr(self, status, getattr(self, status) + 1)
        self._render()

    def _render(self) -> None:
        elapsed = time.monotonic() - self._started_at
        rate = self.done / elapsed if elapsed > 0 else 0
        remaining = self.total - self.done
        eta_seconds = remaining / rate if rate > 0 else 0

        line = (
            f"\r{self.done}/{self.total} "
            f"(✓{self.success} dup:{self.duplicate} inv:{self.invalid} fail:{self.failed}) "
            f"— {rate:.1f}/s — ETA {self._format_duration(eta_seconds)}   "
        )
        sys.stdout.write(line)
        sys.stdout.flush()

    def finish(self) -> None:
        sys.stdout.write("\n")
        sys.stdout.flush()

    @staticmethod
    def _format_duration(seconds: float) -> str:
        seconds = int(seconds)
        h, rem = divmod(seconds, 3600)
        m, s = divmod(rem, 60)
        if h:
            return f"{h}h{m:02d}m"
        if m:
            return f"{m}m{s:02d}s"
        return f"{s}s"
