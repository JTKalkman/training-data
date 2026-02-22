import { User } from "./auth"
import { Flash } from "./flash"

export interface PageProps {
  [key: string]: unknown
  flash?: Flash
  auth?: {
    user: User
  }
}
