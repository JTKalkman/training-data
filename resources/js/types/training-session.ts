import { HeartRateZone } from './heart-rate-zone';
import { SportType } from './sport-type'
import { TrainingSummary } from './training-summary'

export interface TrainingSession {
  id: string;
  startedAt: string;
  startedAtHuman: string;
  duration: number;
  durationHuman: string;
  year: string;
  week: string;
  sportType: SportType;
  trainingSummary?: TrainingSummary;
  heartRateZones: HeartRateZone[];
};
