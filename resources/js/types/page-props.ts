import type { User } from './auth'
import type { Flash } from './flash'

export interface PageProps {
  [key: string]: unknown;
  name?: string;
  auth?: {
    user: User;
  };
  sidebarOpen?: boolean;
  flash?: Flash;
};
