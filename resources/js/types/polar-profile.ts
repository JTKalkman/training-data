export interface PolarProfile {
    id: number;
    first_name: string | null;
    last_name: string | null;
    linked_at: string;
    unlinked_at: string | null;
    last_synced_at: string | null;
    sync_status: 'ok' | 'warning' | 'error' | 'pending';
};
