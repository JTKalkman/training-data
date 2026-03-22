import { HeartRateZone } from './heart-rate-zone';
import { SportType } from './sport-type'
import { TrainingSummary } from './training-summary'

export interface TrainingSession {
  id: string;
  startedAt: string;
  startedAtHuman: string;
  duration: number;
  durationHuman: string;
  notes?: string;
  rating?: number;
  year: string;
  week: string;
  sportType: SportType;
  trainingSummary?: TrainingSummary;
  heartRateZones: HeartRateZone[];
};
