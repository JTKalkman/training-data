import { RouteDataPoint } from '@/types';
import axios from 'axios';
import { ref } from 'vue';
import { route } from 'ziggy-js';

export function useRouteData(sessionId: string) {
  const data = ref<RouteDataPoint[] | null>(null);
  const loading = ref(true);
  const error = ref<string | null>(null);

  const fetch = async () => {
    loading.value = true;
    try {
      const response = await axios.get(route('sessions.route-data', { session: sessionId }));
      data.value = response.data
    } catch (e) {
      if (Error.isError(e)) {
        console.error(e.message); // Or Sentry
      }
      error.value = 'Failed to get the data.';
    } finally {
      loading.value = false;
    }
  }

  return { data, loading, error, fetch };
}