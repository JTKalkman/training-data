## Storage of Raw Measurement Data

Raw time-series measurement data (e.g. second-by-second heart rate samples) is stored as JSON files instead of relational database records.

### Rationale:

- A single multi-hour session can contain 10,000+ samples.
- At scale, storing these as individual rows would lead to very large datasets.
- The relational database is used for domain entities and aggregated session data.
- Time-series samples are stored separately to keep the domain model focused and scalable.
- In a real-world production setup, this could be backed by object storage (e.g. S3) or a dedicated time-series database.
