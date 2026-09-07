# Training Data Uploader

Uploads Polar export files to the trainingsdata API.

It reads `training-session_*.json` files from either a directory or a
`.zip` export, and posts each one to the trainingsdata API. Progress and
results are tracked in a local SQLite file, so the import can be stopped
and resumed without re-uploading files that already succeeded.

## Requirements

- Python 3.10 or later
- `requests` (`pip install requests`)

## Configuration

The tool needs two values, set as environment variables or in a `.env`
file next to `main.py`:

```
TRAININGSDATA_BASE_URL=https://your-trainingsdata-instance.example.com
TRAININGSDATA_TOKEN=your-api-token
```

If either is missing, the tool exits with an error explaining what's
needed.

## Usage

Run against a directory of export files:

```
python main.py /path/to/export/directory
```

Or directly against a Polar export zip:

```
python main.py /path/to/export.zip
```

### Dry run

Add `--dry-run` to see what would be uploaded without sending anything:

```
python main.py /path/to/export.zip --dry-run
```

### Custom state file

By default, progress is tracked in `import_state.sqlite3` in the current
directory. Use `--state-db` to change this, for example if you're
running multiple imports side by side:

```
python main.py /path/to/export.zip --state-db my_import.sqlite3
```

## How it works

1. All `training-session_*.json` files are found and counted first, so
   progress can show a total and an ETA.
2. Each file is read and parsed. If a file already has a terminal result
   (success, duplicate, or invalid) in the state file, it's skipped.
3. Each remaining file is uploaded one at a time, with a minimum delay
   between requests to respect rate limits.
4. The result of each upload is recorded in the state file:
   - **success**: the session was created (HTTP 201)
   - **duplicate**: the session already existed (HTTP 200)
   - **invalid**: the file could not be parsed, or the API rejected it
     (HTTP 422)
   - **failed**: a network error or unexpected server response, after
     retries were exhausted
5. Failed requests are retried with backoff, up to 3 attempts. A 429
   response waits for the `Retry-After` value before retrying.
6. If the API returns 401 or 403, the import stops immediately, since
   this means the token is invalid or lacks permission and retrying
   won't help.

## Resuming an import

Since results are stored per filename in the state database, running the
same command again will skip files that already succeeded, are marked
as duplicates, or were marked invalid. Files that failed will be retried,
up to 5 attempts total, after which they're treated as done and skipped
too.

To force a full re-import, delete the state database file (or point
`--state-db` at a new one).

## Summary output

At the end of a run, a summary of all statuses in the state database is
printed, for example:

```
--- Import summary ---
success: 812
duplicate: 14
invalid: 3
```