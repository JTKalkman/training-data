export function usePace() {
  function formatPace(seconds: number): string {
    if (!seconds || seconds <= 0) return '–';

    const min = Math.floor(seconds / 60);
    const sec = String(seconds % 60).padStart(2, '0');
    return `${min}:${sec}`;
  }

  return { formatPace };
}
