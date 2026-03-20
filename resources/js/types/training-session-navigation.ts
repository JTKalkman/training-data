interface TrainingSessionNavigationItem {
    id: number | null;
    url: string | null;
}

export interface TrainingSessionNavigation {
    prev: TrainingSessionNavigationItem;
    next: TrainingSessionNavigationItem;
}
