const heartRateZoneColorsMap: Record<string, string> = {
  blue:   'rgba(50, 150, 225, 0.10)',
  green:  'rgba(34, 197, 94, 0.25)',
  yellow: 'rgba(234, 179, 8, 0.25)',
  orange: 'rgba(249, 115, 22, 0.30)',
  red:    'rgba(239, 68, 68, 0.30)',
};

export function heartRateZoneColor(color: string): string {
  return heartRateZoneColorsMap[color] ?? 'rgba(148, 163, 246, 0.15)';
}
