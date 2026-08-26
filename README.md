# Training Data Viewer

A Laravel demo application for importing and visualizing training sessions from Polar heart rate monitors (with potential support for other sources like Garmin in the future).

## Project Scope

This demo project focuses on:
- Importing Polar CSV exports
- Linking user accounts to Polar via OAuth 2.0
- Storing and retrieving heart rate data efficiently
- Visualizing training sessions and heart rate zones
- Automatic syncing
- Training feedback form
- GPS / route tracking

Out of scope for this demo:
- Cadence, power, distance, speed, altitude metrics
- Multi-user features beyond basic authentication
- Training analysis based on data and feedback

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

### Map Data Downsampling

- Map route/GPS data is downsampled using the **Visvalingam-Whyatt algorithm**
- Implementation: `VisvalingamSimplifier` class
- Why downsampling?
    - GPS traces can contain thousands of redundant coordinate points
    - Reduces frontend memory footprint and improves map rendering performance
    - Algorithm preserves perceptually important points while removing noise
- Algorithm approach:
    - Iteratively removes points with the smallest triangulated area
    - Continues until reaching target sample ratio (default: 20% of original)
    - Maintains visual accuracy of route trace on the map

## Known Limitations / Future Improvements

- Multi-user support limited to demo login
- Timezones: uses server timezone, no per-user timezone handling

## Testing

- Seeder + factories generate realistic demo data
- CSV parsing and transformation are unit-testable
- ChartData and Duration helpers are reusable and easily tested

## One-time server setup

### Server requirements:

See the [Laravel deployment requirements](https://laravel.com/docs/deployment#server-requirements) for PHP version and extensions.

Additionally required:
- Nginx
- Supervisor (for queue workers, see `deploy/trainingsdata-worker.conf`)

### Ngnix

Install `nginx-trainingsdata.conf` in `/etc/nginx/sites-available/trainingsdata`.

### Worker configuration

Install `deploy/trainingsdata-worker.conf` in `/etc/supervisor/conf.d/trainingsdata-worker.conf` and then:

```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start trainingsdata-worker:*
```

### Scheduler

Install `deploy/trainingsdata-scheduler` in `/etc/cron.d` and make sure this has the correct rights.

```
sudo chmod 644 /etc/cron.d/trainingsdata-scheduler
```

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
sudo -u www-data php artisan app:test-polar-sync {userId}
```

This command:
- Syncs the user's training sessions from Polar API
- Parses and stores session metadata, raw heart rate data, map data, and additional metrics (speed, cadence, altitude, etc.)
- Imports data that can be viewed in the training session dashboard

**Example:**
```
sudo -u www-data php artisan app:test-polar-sync 1
```

## Demo Login

- Email: `test@example.com`
- Password: `password`

## Running the Application

```
composer run dev
```

## API Documentation

The application provides a REST API for accessing training data and user information.

### Base URL
```
/api/v1
```

### Authentication

Most endpoints require authentication using Bearer tokens (Sanctum). After logging in, use the returned token in the `Authorization` header:

```
Authorization: Bearer {token}
```

### Available Endpoints

#### Authentication

**Login**
```
POST /auth/login
```
- **Description:** Authenticate user and receive access token
- **Body:** `{ "email": "user@example.com", "password": "password" }`
- **Returns:** Access token for use in subsequent requests

**Refresh Token**
```
POST /auth/refresh
```
- **Description:** Refresh the authentication token
- **Requires:** Authentication

**Logout**
```
DELETE /auth/logout
```
- **Description:** Revoke the current authentication token
- **Requires:** Authentication

#### Profiles

**Get Profiles**
```
GET /profiles
```
- **Description:** Retrieve all linked user profiles (e.g., Polar profiles)
- **Requires:** Authentication
- **Returns:** List of user profiles with integration details

#### Devices

**Get Devices**
```
GET /devices
```
- **Description:** Retrieve all registered devices
- **Requires:** Authentication
- **Returns:** List of devices (e.g., Polar heart rate monitors)

#### Training Sessions

**Get Training Sessions**
```
GET /training-sessions
```
- **Description:** Retrieve all training sessions for the authenticated user
- **Requires:** Authentication
- **Returns:** List of training sessions with metadata (duration, average HR, sport type, etc.)

**Get Training Session Details**
```
GET /training-sessions/{trainingSession}
```
- **Description:** Retrieve detailed information for a specific training session
- **Requires:** Authentication
- **Parameters:** `trainingSession` (session ID or slug)
- **Returns:** Full session details including heart rate zones breakdown

**Get Training Session Sample Data**
```
GET /training-sessions/{trainingSession}/sample-data
```
- **Description:** Retrieve per-second heart rate samples for charting
- **Requires:** Authentication
- **Parameters:** `trainingSession` (session ID or slug)
- **Returns:** Chart-ready data with heart rate values, timestamps, and zone information

**Get Training Session Route Data**
```
GET /training-sessions/{trainingSession}/route-data
```
- **Description:** Retrieve GPS route/map data for the training session
- **Requires:** Authentication
- **Parameters:** `trainingSession` (session ID or slug)
- **Returns:** Map coordinates and geographical data

#### Sport Types

**Get Sport Types**
```
GET /sport-types
```
- **Description:** Retrieve all available sport types (e.g., running, cycling)
- **Requires:** Authentication
- **Returns:** List of sport types used in training sessions
