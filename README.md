# Training Data Viewer

A Laravel demo application for importing and visualizing training sessions from Polar heart rate monitors (with potential support for other sources like Garmin in the future).

## Project Scope

This demo project focuses on:
- Importing Polar CSV exports
- Linking user accounts to Polar via OAuth 2.0
- Storing and retrieving heart rate data efficiently
- Visualizing training sessions and heart rate zones

Out of scope for this demo:
- GPS / route tracking (maps are parsed and stored but not yet displayed)
- Cadence, power, distance, speed, altitude metrics (parsed and stored but not yet displayed in frontend)
- Training feedback forms (planned for future)
- Multi-user features beyond basic authentication
- Real-time automatic syncing (planned for future)

## Architecture

- **Backend:** Laravel 12, Eloquent ORM, Controllers + JSON Resources
- **Frontend:** Inertia.js + Vue 3 + TypeScript, single-page demo app
- **Data flow:**
    - CSV → Parser → DB + raw JSON files
    - Backend provides aggregated session data and chart-ready raw data
- **Helpers / Domain Logic:**
    - Duration class: reusable methods to format seconds → human-friendly or chart-friendly strings
    - ChartData: transforms raw JSON session data into frontend-ready structure (labels, HR, zones)

## Polar Account Integration

Users can link their Polar account via OAuth 2.0 to enable automatic data synchronization (future feature):
- **OAuth Flow:** Redirects to Polar's authentication server; tokens are securely stored
- **Token Storage:** Access tokens and expiration times are stored in the `polar_profiles` table
- **Account Status:** Users can disconnect their Polar account from account settings
- **Note:** Disconnecting removes the stored credentials and will prevent future sync operations until re-linked

## Data Storage & Design Decisions

### Raw Measurement Data

Raw per-second heart rate samples are stored as JSON files rather than relational rows.

#### Why JSON?

- Multi-hour sessions can contain 10,000+ samples
- Storing each sample as a DB row would quickly bloat the database
- DB is reserved for domain entities and aggregated session info
- Allows lazy loading of raw data for charts
- In production, this could be backed by object storage (S3) or a time-series DB

### Heart Rate Zones

- Zones are session-specific and snapshotted at import time
- Stored in heart_rate_zones table linked to the session
- Zones depend on:
    - User’s max HR (age-dependent)
    - Sport type (cycling vs running)
    - Historical accuracy: past sessions show the zones as they were at the time
- Frontend maps zone colors (blue/green/yellow/orange/red) to Tailwind classes

### Frontend / Week & Session Views

- Week view:
    - Shows all sessions for a given week
    - Navigation: previous / next week buttons
    - Sessions displayed flat; frontend groups per day
- Session details view:
    - Session metadata (sport type, duration, HR min/avg/max)
    - Heart rate zones breakdown
    - Chart of per-second heart rate data (lazy-loaded)

### Chart / Raw Data Handling

- Chart library: Chart.js
- Performance:
    - Line only (no points), thin line for clarity
    - Lazy load raw JSON to reduce frontend memory footprint
    - X-axis labels formatted with Duration::clock()
- Raw data transform:
    - ChartData class handles conversion of raw JSON → chart-ready structure
    - Duration labels, HR values, optional zones
    - Controller remains slim (sampleData endpoint just fetches transformed data)

## Known Limitations / Future Improvements

- Multi-user support limited to demo login
- Chart: no zooming or downsampling (full dataset shown)
- File path caching not compatible with S3 / cloud storage
- Feedback form / session notes → conceptually planned, not implemented
- Timezones: uses server timezone, no per-user timezone handling

## Testing

- Seeder + factories generate realistic demo data
- CSV parsing and transformation are unit-testable
- ChartData and Duration helpers are reusable and easily tested

## Installation

```
# Clone and install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed
```

## Manual Polar API Testing

A console command is available for manually testing and syncing training sessions from the Polar API:

```
php artisan app:test-polar-sync {userId}
```

This command:
- Syncs the user's training sessions from Polar API
- Parses and stores session metadata, raw heart rate data, map data, and additional metrics (speed, cadence, altitude, etc.)
- Imports data that can be viewed in the training session dashboard

**Example:**
```
php artisan app:test-polar-sync 1
```

## Demo Login

- Email: `test@example.com`
- Password: `password`

## Running the Application

```
composer run dev
```
