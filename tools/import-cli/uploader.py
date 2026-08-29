from __future__ import annotations

import json
import time
from dataclasses import dataclass

import requests

from file_reader import SourceFile
from state import UploadResult


class AuthenticationError(Exception):
    """Raised on a 401, the whole run should stop, not just this file."""


@dataclass(frozen=True)
class ExercisePayload:
    external_id: str
    started_at: str
    payload: dict


class Uploader:
    def __init__(
        self,
        base_url: str,
        token: str,
        dry_run: bool = False,
        min_interval_seconds: float = 1.0,
        max_retries: int = 3,
    ):
        self.base_url = base_url.rstrip("/")
        self.dry_run = dry_run
        self.min_interval_seconds = min_interval_seconds
        self.max_retries = max_retries
        self._last_request_at: float | None = None

        self._session = requests.Session()
        self._session.headers.update({
            "Authorization": f"Bearer {token}",
            "Accept": "application/json",
        })

    def upload(self, file: SourceFile) -> UploadResult:
        try:
            exercise = self._build_payload(file)
        except (json.JSONDecodeError, KeyError, IndexError, ValueError) as e:
            return UploadResult(status="invalid", error=f"Could not parse file: {e}")

        if self.dry_run:
            print(f"[dry-run] would upload {file.name} (externalId={exercise.external_id})")
            return UploadResult(status="success")

        return self._upload_with_retry(exercise)

    def _build_payload(self, file: SourceFile) -> ExercisePayload:
        data = json.loads(file.content)
        exercises = data.get("exercises", [])

        if len(exercises) != 1:
            raise ValueError(f"Expected exactly 1 exercise, found {len(exercises)}")

        exercise = exercises[0]

        return ExercisePayload(
            external_id=str(exercise["identifier"]["id"]),
            started_at=exercise["startTime"],
            payload=data,
        )

    def _upload_with_retry(self, exercise: ExercisePayload) -> UploadResult:
        last_error = None

        for attempt in range(1, self.max_retries + 1):
            self._respect_rate_limit()

            try:
                response = self._session.post(
                    f"{self.base_url}/training-sessions",
                    json={
                        "platform": "polar",
                        "importMethod": "export",
                        "externalId": exercise.external_id,
                        "startedAt": exercise.started_at,
                        "payload": exercise.payload,
                    },
                    timeout=30,
                )
            except requests.RequestException as e:
                last_error = str(e)
                self._backoff(attempt)
                continue

            if response.status_code == 401:
                raise AuthenticationError("Token rejected (401) — stopping import.")

            if response.status_code == 201:
                return UploadResult(status="success")

            if response.status_code == 200:
                return UploadResult(status="duplicate")

            if response.status_code == 422:
                return UploadResult(status="invalid", error=response.text)

            if response.status_code == 429:
                retry_after = int(response.headers.get("Retry-After", 15))
                time.sleep(retry_after)
                continue

            # 5xx or anything unexpected — retry
            last_error = f"HTTP {response.status_code}: {response.text[:200]}"
            self._backoff(attempt)

        return UploadResult(status="failed", error=last_error)

    def _respect_rate_limit(self) -> None:
        if self._last_request_at is not None:
            elapsed = time.monotonic() - self._last_request_at
            wait = self.min_interval_seconds - elapsed
            if wait > 0:
                time.sleep(wait)
        self._last_request_at = time.monotonic()

    def _backoff(self, attempt: int) -> None:
        time.sleep(min(2 ** attempt, 30))  # 2s, 4s, 8s, capped at 30s
