
import zipfile

from typing import Iterator
from pathlib import Path

from dataclasses import dataclass
from fnmatch import fnmatch


TRAINING_SESSION_PATTERN = "training-session_*.json"


@dataclass(frozen=True)
class SourceFile:
    name: str       # basename only, e.g. "training-session_2020-07-09T18-40-55_..._....json"
    content: bytes  # raw file content, not yet parsed

def iter_training_session_files(source: str) -> Iterator[SourceFile]:
    path = Path(source)
    
    if path.is_dir():
        yield from _iter_directory(path)
    elif path.is_file() and path.suffix.lower() == ".zip":
        yield from _iter_zip(path)
    else:
        raise ValueError(f"Source is not a directory or a .zip file: {source}")

def _iter_directory(path: Path) -> Iterator[SourceFile]:
    for file in sorted(path.glob(TRAINING_SESSION_PATTERN)):
        yield SourceFile(name=file.name, content=file.read_bytes())

def _iter_zip(path: Path) -> Iterator[SourceFile]:
    with zipfile.ZipFile(path) as zf:
        matching = sorted(
            entry for entry in zf.namelist() if _is_training_session_entry(entry)
        )
        for entry in matching:
            with zf.open(entry) as f:
                content = f.read()
            yield SourceFile(name=Path(entry).name, content=content)

def _is_training_session_entry(entry: str) -> bool:
    if entry.endswith("/"):
        return False # Directory entry, not a file.
    if "__MACOSX" in entry:
        return False # Skip macOS zip metadata junk, not expected in data from Polar.

    return fnmatch(Path(entry).name, TRAINING_SESSION_PATTERN)

def count_training_session_files(source: str) -> int:
    """Count matching files without reading their content, cheap, for progress totals."""
    path = Path(source)

    if path.is_dir():
        return sum(1 for _ in path.glob(TRAINING_SESSION_PATTERN))
    elif path.is_file() and path.suffix.lower() == ".zip":
        with zipfile.ZipFile(path) as zf:
            return sum(1 for entry in zf.namelist() if _is_training_session_entry(entry))
    else:
        raise ValueError(f"Source is not a directory or .zip file: {source}")
