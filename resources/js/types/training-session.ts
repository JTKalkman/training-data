import { HeartRateZone } from './heart-rate-zone';
import { SportType } from './sport-type'
import { TrainingSummary } from './training-summary'

export interface TrainingSession {
  id: string;
  started_at: string;
  started_at_human: string;
  duration: number;
  duration_human: string;
  year: string;
  week: string;
  sport_type: SportType;
  training_summary?: TrainingSummary;
  heart_rate_zones: HeartRateZone[];
};
