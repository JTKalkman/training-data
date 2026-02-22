import { User } from './auth'
import { Flash } from './flash'

export interface PageProps {
  [key: string]: unknown;
  name?: string;
  auth?: {
    user: User;
  };
  sidebarOpen?: boolean;
  flash?: Flash;
};
