# state.py
from __future__ import annotations

import sqlite3
from dataclasses import dataclass
from datetime import datetime, timezone
from types import TracebackType


TERMINAL_STATUSES = {"success", "duplicate", "invalid"}


@dataclass(frozen=True)
class UploadResult:
    status: str # "success" | "duplicate" | "invalid" | "failed"
    error: str | None = None


class ImportState:
    def __init__(self, db_path: str, max_attempts: int = 5):
        self.max_attempts = max_attempts
        self._conn = sqlite3.connect(db_path)
        self._conn.execute("""
            CREATE TABLE IF NOT EXISTS import_files (
                filename TEXT PRIMARY KEY,
                status TEXT NOT NULL,
                attempts INTEGER NOT NULL DEFAULT 0,
                last_error TEXT,
                updated_at TEXT NOT NULL
            )
        """)
        self._conn.commit()

    def __enter__(self) -> "ImportState":
        return self

    def __exit__(
        self,
        exc_type: type[BaseException] | None,
        exc_value: BaseException | None,
        traceback: TracebackType | None,
    ) -> None:
        self.close()

    def is_done(self, filename: str) -> bool:
        row = self._conn.execute(
            "SELECT status, attempts FROM import_files WHERE filename = ?",
            (filename,),
        ).fetchone()

        if row is None:
            return False

        status, attempts = row
        if status in TERMINAL_STATUSES:
            return True
        return attempts >= self.max_attempts

    def record(self, filename: str, result: UploadResult) -> None:
        now = datetime.now(timezone.utc).isoformat()
        self._conn.execute("""
            INSERT INTO import_files (filename, status, attempts, last_error, updated_at)
            VALUES (?, ?, 1, ?, ?)
            ON CONFLICT(filename) DO UPDATE SET
                status = excluded.status,
                attempts = attempts + 1,
                last_error = excluded.last_error,
                updated_at = excluded.updated_at
        """, (filename, result.status, result.error, now))
        self._conn.commit()

    def print_summary(self) -> None:
        rows = self._conn.execute(
            "SELECT status, COUNT(*) FROM import_files GROUP BY status"
        ).fetchall()
        print("\n--- Import summary ---")
        for status, count in rows:
            print(f"{status}: {count}")

    def close(self) -> None:
        self._conn.close()
