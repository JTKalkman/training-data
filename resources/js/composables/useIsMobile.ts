export function useIsMobile (): boolean {
  return window.matchMedia('(pointer: coarse)').matches;
}
