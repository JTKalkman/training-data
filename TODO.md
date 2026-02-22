# TODO - Training Data Viewer

## Code improvements or tasks.
- [X] Show linked Polar accounts, just a barebone integration.
- [ ] Snake case in data, also for Vue.
- [X] Create branch for unrelated changes such as Telescope installation.
- [X] Use Ziggy.
- [X] Use TypeScript.
 
## Polar Integration - Sync Implementation

### Sync Command & Scheduler
- [ ] Create `SyncPolarTrainingSessionsCommand` (artisan command)
- [ ] Implement sync logic to fetch new training sessions from Polar API
- [ ] Register command in task scheduler (daily or hourly)

### Disconnection Handling
- [ ] When a user has disconnected their Polar account (removed `PolarProfile`), sync should gracefully handle the missing credentials
- [ ] Log sync failures with context (user ID, reason: "Polar account not linked")
- [ ] Display user-facing alert/notification when sync fails due to disconnection
  - Option 1: In-app toast notification on dashboard/settings
  - Option 2: Email notification to user
- [ ] Suggest user re-link their Polar account

### Token Refresh
- [ ] Implement token refresh logic when access token expires
- [ ] Handle refresh token rotation (if Polar API provides it)
- [ ] Add retry logic for sync when tokens are refreshed

### (Manual) import

- [ ] ZIP/JSON bulk import historical data
- [ ] Version-aware parser (exportVersion difference)
- [ ] Deduplicate import vs sync
- [ ] Import progress tracking

## Front end

- [ ] Add Vue component for toast messages. (Vue Toastification, shadcn-vue -> Sonner component, or make something myself.)

## Future Features

### Garmin Integration
- [ ] Scope out Garmin API requirements
- [ ] Implement Garmin OAuth flow (similar to Polar)
- [ ] Create `app/Support/API/Garmin/` directory structure
- [ ] Add sync support for Garmin data

### Real-time Sync
- [ ] Implement webhook support for real-time updates from Polar/Garmin
- [ ] Queue-based processing for incoming data

### Training Feedback
- [ ] Add training feedback forms post-session
- [ ] Store feedback in database
- [ ] Display feedback on session details view

### Multi-Sport Analysis
- [ ] Cross-sport performance comparisons
- [ ] Sport-specific training zones and recommendations
