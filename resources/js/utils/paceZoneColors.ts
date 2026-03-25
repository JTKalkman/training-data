const paceZoneColorMap: Record<string, string> = {
  blue:   'rgba(0, 150, 255, 0.10)',
  green:  'rgba(34, 197, 94, 0.25)',
  yellow: 'rgba(234, 179, 8, 0.25)',
  orange: 'rgba(249, 115, 22, 0.30)',
  red:    'rgba(239, 68, 68, 0.30)',
};

export function paceZoneColor(color: string): string {
  return paceZoneColorMap[color] ?? 'rgba(148, 163, 246, 0.15)';
}
