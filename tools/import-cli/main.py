#!/usr/bin/env python3

from __future__ import annotations

import argparse

from file_reader import iter_training_session_files
from state import ImportState
from uploader import Uploader
from config import load_config


def main():
    config = load_config()

    parser = argparse.ArgumentParser()
    parser.add_argument("source", help="Path to export zip or directory")
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("--state-db", default="import_state.sqlite3")
    args = parser.parse_args()

    state = ImportState(args.state_db)
    uploader = Uploader(base_url=config.base_url, token=config.token, dry_run=args.dry_run)

    with ImportState(args.state_db) as state:
        for file in iter_training_session_files(args.source):
            if state.is_done(file.name):
                continue
            result = uploader.upload(file)
            state.record(file.name, result)
        state.print_summary()


if __name__ == "__main__":
    main()
