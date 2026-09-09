<?php

namespace Database\Seeders;

use App\Models\DataSource;
use App\Models\ExternalSportTypeMapping;
use App\Models\SportType;
use Illuminate\Database\Seeder;

class SportTypeMappingSeeder extends Seeder
{
    /**
     * Maps Polar's external sport type codes onto our internal
     * sport_types.name. Run SportTypeSeeder first, any 'sport_type'
     * value below that doesn't exist yet will fail loudly rather than
     * silently create a mismatched row.
     *
     * external_id (export-specific ID) is intentionally left out here,
     * it's only known for a handful of sports so far, reverse-engineered
     * by date-matching exercises. Fill those in separately once the
     * export/API mapping is more complete.
     */
    protected array $mappings = [
            ['sport_type' => 'aerobics', 'external_name' => 'AEROBICS', 'external_label' => 'Aerobics'],
            ['sport_type' => 'dog-agility', 'external_name' => 'AGILITY', 'external_label' => 'Dog agility'],
            ['sport_type' => 'football', 'external_name' => 'AMERICAN_FOOTBALL', 'external_label' => 'Football'],
            ['sport_type' => 'aqua-fitness', 'external_name' => 'AQUATICS', 'external_label' => 'Aqua fitness'],
            ['sport_type' => 'backcountry-skiing', 'external_name' => 'BACKCOUNTRY_SKIING', 'external_label' => 'Backcountry skiing'],
            ['sport_type' => 'badminton', 'external_name' => 'BADMINTON', 'external_label' => 'Badminton'],
            ['sport_type' => 'ballet', 'external_name' => 'BALLET_DANCING', 'external_label' => 'Ballet'],
            ['sport_type' => 'ballroom', 'external_name' => 'BALLROOM_DANCING', 'external_label' => 'Ballroom'],
            ['sport_type' => 'baseball', 'external_name' => 'BASEBALL', 'external_label' => 'Baseball'],
            ['sport_type' => 'basketball', 'external_name' => 'BASKETBALL', 'external_label' => 'Basketball'],
            ['sport_type' => 'beach-tennis', 'external_name' => 'BEACH_TENNIS', 'external_label' => 'Beach tennis'],
            ['sport_type' => 'beach-volley', 'external_name' => 'BEACH_VOLLEYBALL', 'external_label' => 'Beach volley'],
            ['sport_type' => 'biathlon', 'external_name' => 'BIATHLON', 'external_label' => 'Biathlon'],
            ['sport_type' => 'body-and-mind', 'external_name' => 'BODY_AND_MIND', 'external_label' => 'Body&Mind'],
            ['sport_type' => 'bootcamp', 'external_name' => 'BOOTCAMP', 'external_label' => 'Bootcamp'],
            ['sport_type' => 'boxing', 'external_name' => 'BOXING', 'external_label' => 'Boxing'],
            ['sport_type' => 'calisthenics', 'external_name' => 'CALISTHENICS', 'external_label' => 'Calisthenics'],
            ['sport_type' => 'circuit-training', 'external_name' => 'CIRCUIT_TRAINING', 'external_label' => 'Circuit training'],
            ['sport_type' => 'core', 'external_name' => 'CORE', 'external_label' => 'Core'],
            ['sport_type' => 'cricket', 'external_name' => 'CRICKET', 'external_label' => 'Cricket'],
            ['sport_type' => 'cross-trainer', 'external_name' => 'CROSS_TRAINER', 'external_label' => 'Cross-trainer'],
            ['sport_type' => 'cross-country-running', 'external_name' => 'CROSS_COUNTRY_RUNNING', 'external_label' => 'Cross-country running'],
            ['sport_type' => 'skiing', 'external_name' => 'CROSS-COUNTRY_SKIING', 'external_label' => 'Skiing'],
            ['sport_type' => 'cycling', 'external_name' => 'CYCLING', 'external_label' => 'Cycling'],
            ['sport_type' => 'climbing', 'external_name' => 'CLIMBING', 'external_label' => 'Climbing'],
            ['sport_type' => 'curling', 'external_name' => 'CURLING', 'external_label' => 'Curling'],
            ['sport_type' => 'dancing', 'external_name' => 'DANCING', 'external_label' => 'Dancing'],
            ['sport_type' => 'downhill-skiing', 'external_name' => 'DOWNHILL_SKIING', 'external_label' => 'Downhill skiing'],
            ['sport_type' => 'duathlon', 'external_name' => 'DUATHLON', 'external_label' => 'Duathlon'],
            ['sport_type' => 'cycling', 'external_name' => 'DUATHLON_CYCLING', 'external_label' => 'Cycling'],
            ['sport_type' => 'running', 'external_name' => 'DUATHLON_RUNNING', 'external_label' => 'Running'],
            ['sport_type' => 'electric-biking', 'external_name' => 'E_BIKE', 'external_label' => 'Electric biking'],
            ['sport_type' => 'esports', 'external_name' => 'ESPORTS', 'external_label' => 'Esports'],
            ['sport_type' => 'field-hockey', 'external_name' => 'FIELD_HOCKEY', 'external_label' => 'Field hockey'],
            ['sport_type' => 'finnish-baseball', 'external_name' => 'FINNISH_BASEBALL', 'external_label' => 'Finnish baseball'],
            ['sport_type' => 'fitness-boxing', 'external_name' => 'FITNESS_BOXING', 'external_label' => 'Fitness boxing'],
            ['sport_type' => 'fitness-dancing', 'external_name' => 'FITNESS_DANCING', 'external_label' => 'Fitness dancing'],
            ['sport_type' => 'fitness-martial-arts', 'external_name' => 'FITNESS_MARTIAL_ARTS', 'external_label' => 'Fitness martial arts'],
            ['sport_type' => 'fitness-racing', 'external_name' => 'FITNESS_RACING', 'external_label' => 'Fitness Racing'],
            ['sport_type' => 'step-workout', 'external_name' => 'FITNESS_STEP', 'external_label' => 'Step workout'],
            ['sport_type' => 'floorball', 'external_name' => 'FLOORBALL', 'external_label' => 'Floorball'],
            ['sport_type' => 'multisport', 'external_name' => 'FREE_MULTISPORT', 'external_label' => 'Multisport'],
            ['sport_type' => 'disc-golf', 'external_name' => 'FRISBEEGOLF', 'external_label' => 'Disc golf'],
            ['sport_type' => 'functional-training', 'external_name' => 'FUNCTIONAL_TRAINING', 'external_label' => 'Functional training'],
            ['sport_type' => 'futsal', 'external_name' => 'FUTSAL', 'external_label' => 'Futsal'],
            ['sport_type' => 'golf', 'external_name' => 'GOLF', 'external_label' => 'Golf'],
            ['sport_type' => 'gravel-cycling', 'external_name' => 'GRAVEL', 'external_label' => 'Gravel cycling'],
            ['sport_type' => 'group-exercise', 'external_name' => 'GROUP_EXERCISE', 'external_label' => 'Group exercise'],
            ['sport_type' => 'gymnastics', 'external_name' => 'GYMNASTICK', 'external_label' => 'Gymnastics'],
            ['sport_type' => 'handball', 'external_name' => 'HANDBALL', 'external_label' => 'Handball'],
            ['sport_type' => 'high-intensity-interval-training', 'external_name' => 'HIIT', 'external_label' => 'High-intensity interval training'],
            ['sport_type' => 'hiking', 'external_name' => 'HIKING', 'external_label' => 'Hiking'],
            ['sport_type' => 'ice-hockey', 'external_name' => 'ICE_HOCKEY', 'external_label' => 'Ice hockey'],
            ['sport_type' => 'ice-skating', 'external_name' => 'ICE_SKATING', 'external_label' => 'Ice skating'],
            ['sport_type' => 'indoor-cycling', 'external_name' => 'INDOOR_CYCLING', 'external_label' => 'Indoor cycling'],
            ['sport_type' => 'indoor-rowing', 'external_name' => 'INDOOR_ROWING', 'external_label' => 'Indoor rowing'],
            ['sport_type' => 'inline-skating', 'external_name' => 'INLINE_SKATING', 'external_label' => 'Inline skating'],
            ['sport_type' => 'jazz', 'external_name' => 'JAZZ_DANCING', 'external_label' => 'Jazz'],
            ['sport_type' => 'jogging', 'external_name' => 'JOGGING', 'external_label' => 'Jogging'],
            ['sport_type' => 'judo', 'external_name' => 'JUDO_MARTIAL_ARTS', 'external_label' => 'Judo'],
            ['sport_type' => 'rope-skipping', 'external_name' => 'JUMP_ROPE', 'external_label' => 'Rope skipping'],
            ['sport_type' => 'kettlebell', 'external_name' => 'KETTLEBELL', 'external_label' => 'Kettlebell'],
            ['sport_type' => 'kickbiking', 'external_name' => 'KICKBIKE', 'external_label' => 'Kickbiking'],
            ['sport_type' => 'kickboxing', 'external_name' => 'KICKBOXING_MARTIAL_ARTS', 'external_label' => 'Kickboxing'],
            ['sport_type' => 'latin', 'external_name' => 'LATIN_DANCING', 'external_label' => 'Latin'],
            ['sport_type' => 'les-mills-barre', 'external_name' => 'LES_MILLS_BARRE', 'external_label' => 'LES MILLS BARRE'],
            ['sport_type' => 'les-mills-bodyattack', 'external_name' => 'LES_MILLS_BODYATTACK', 'external_label' => 'LES MILLS BODYATTACK'],
            ['sport_type' => 'les-mills-bodybalance', 'external_name' => 'LES_MILLS_BODYBALANCE', 'external_label' => 'LES MILLS BODYBALANCE'],
            ['sport_type' => 'les-mills-bodycombat', 'external_name' => 'LES_MILLS_BODYCOMBAT', 'external_label' => 'LES MILLS BODYCOMBAT'],
            ['sport_type' => 'les-mills-bodyjam', 'external_name' => 'LES_MILLS_BODYJAM', 'external_label' => 'LES MILLS BODYJAM'],
            ['sport_type' => 'les-mills-bodypump', 'external_name' => 'LES_MILLS_BODYPUMP', 'external_label' => 'LES MILLS BODYPUMP'],
            ['sport_type' => 'les-mills-bodystep', 'external_name' => 'LES_MILLS_BODYSTEP', 'external_label' => 'LES MILLS BODYSTEP'],
            ['sport_type' => 'les-mills-cxworx', 'external_name' => 'LES_MILLS_CXWORKS', 'external_label' => 'LES MILLS CXWORX'],
            ['sport_type' => 'les-mills-grit-athletic', 'external_name' => 'LES_MILLS_GRIT_ATHLETIC', 'external_label' => 'LES MILLS GRIT Athletic'],
            ['sport_type' => 'les-mills-grit-cardio', 'external_name' => 'LES_MILLS_GRIT_CARDIO', 'external_label' => 'LES MILLS GRIT Cardio'],
            ['sport_type' => 'les-mills-grit-strength', 'external_name' => 'LES_MILLS_GRIT_STRENGTH', 'external_label' => 'LES MILLS GRIT Strength'],
            ['sport_type' => 'les-mills-rpm', 'external_name' => 'LES_MILLS_RPM', 'external_label' => 'LES MILLS RPM'],
            ['sport_type' => 'les-mills-shbam', 'external_name' => 'LES_MILLS_SHBAM', 'external_label' => 'LES MILLS SH\'BAM'],
            ['sport_type' => 'les-mills-sprint', 'external_name' => 'LES_MILLS_SPRINT', 'external_label' => 'LES MILLS SPRINT'],
            ['sport_type' => 'les-mills-tone', 'external_name' => 'LES_MILLS_TONE', 'external_label' => 'LES MILLS TONE'],
            ['sport_type' => 'les-mills-trip', 'external_name' => 'LES_MILLS_TRIP', 'external_label' => 'LES MILLS TRIP'],
            ['sport_type' => 'mobility-dynamic', 'external_name' => 'MOBILITY_DYNAMIC', 'external_label' => 'Mobility (dynamic)'],
            ['sport_type' => 'mobility-static', 'external_name' => 'MOBILITY_STATIC', 'external_label' => 'Mobility (static)'],
            ['sport_type' => 'modern', 'external_name' => 'MODERN_DANCING', 'external_label' => 'Modern'],
            ['sport_type' => 'car-racing', 'external_name' => 'MOTORSPORTS_CAR_RACING', 'external_label' => 'Car racing'],
            ['sport_type' => 'enduro', 'external_name' => 'MOTORSPORTS_ENDURO', 'external_label' => 'Enduro'],
            ['sport_type' => 'hard-enduro', 'external_name' => 'MOTORSPORTS_HARD_ENDURO', 'external_label' => 'Hard Enduro'],
            ['sport_type' => 'motocross', 'external_name' => 'MOTORSPORTS_MOTOCROSS', 'external_label' => 'Motocorss'],
            ['sport_type' => 'road-racing', 'external_name' => 'MOTORSPORTS_ROADRACING', 'external_label' => 'Road racing'],
            ['sport_type' => 'snocross', 'external_name' => 'MOTORSPORTS_SNOCROSS', 'external_label' => 'Snocross'],
            ['sport_type' => 'mountain-biking', 'external_name' => 'MOUNTAIN_BIKING', 'external_label' => 'Mountain biking'],
            ['sport_type' => 'nordic-walking', 'external_name' => 'NORDIC_WALKING', 'external_label' => 'Nordic walking'],
            ['sport_type' => 'obstacle-course-racing', 'external_name' => 'OBSTACLE_COURSE_RACING', 'external_label' => 'Obstacle course racing'],
            ['sport_type' => 'off-road-duathlon', 'external_name' => 'OFFROADDUATHLON', 'external_label' => 'Off-road duathlon'],
            ['sport_type' => 'mountain-biking', 'external_name' => 'OFFROADDUATHLON_CYCLING', 'external_label' => 'Mountain biking'],
            ['sport_type' => 'trail-running', 'external_name' => 'OFFROADDUATHLON_RUNNING', 'external_label' => 'Trail running'],
            ['sport_type' => 'off-road-triathlon', 'external_name' => 'OFFROADTRIATHLON', 'external_label' => 'Off-road triathlon'],
            ['sport_type' => 'mountain-biking', 'external_name' => 'OFFROADTRIATHLON_CYCLING', 'external_label' => 'Mountain biking'],
            ['sport_type' => 'trail-running', 'external_name' => 'OFFROADTRIATHLON_RUNNING', 'external_label' => 'Trail running'],
            ['sport_type' => 'open-water-swimming', 'external_name' => 'OFFROADTRIATHLON_SWIMMING', 'external_label' => 'Open water swimming'],
            ['sport_type' => 'open-water-swimming', 'external_name' => 'OPEN_WATER_SWIMMING', 'external_label' => 'Open water swimming'],
            ['sport_type' => 'orienteering', 'external_name' => 'ORIENTEERING', 'external_label' => 'Orienteering'],
            ['sport_type' => 'mountain-bike-orienteering', 'external_name' => 'ORIENTEERING_MTB', 'external_label' => 'Mountain bike orienteering'],
            ['sport_type' => 'ski-orienteering', 'external_name' => 'ORIENTEERING_SKI', 'external_label' => 'Ski orienteering'],
            ['sport_type' => 'other-indoor', 'external_name' => 'OTHER_INDOOR', 'external_label' => 'Other indoor'],
            ['sport_type' => 'other-outdoor', 'external_name' => 'OTHER_OUTDOOR', 'external_label' => 'Other outdoor'],
            ['sport_type' => 'padel-racing', 'external_name' => 'PADEL', 'external_label' => 'Padel racing'],
            ['sport_type' => 'handcycling', 'external_name' => 'PARASPORTS_HAND_CYCLING', 'external_label' => 'Handcycling'],
            ['sport_type' => 'sled-hockey', 'external_name' => 'PARASPORTS_SLED_HOCKEY', 'external_label' => 'Sled hockey'],
            ['sport_type' => 'adaptive-water-skiing', 'external_name' => 'PARASPORTS_WATER_SKIING', 'external_label' => 'Adaptive water skiing'],
            ['sport_type' => 'wheelchair-racing', 'external_name' => 'PARASPORTS_WHEELCHAIR', 'external_label' => 'Wheelchair racing'],
            ['sport_type' => 'wheelchair-basketball', 'external_name' => 'PARASPORTS_WHEELCHAIR_BASKETBALL', 'external_label' => 'Wheelchair basketball'],
            ['sport_type' => 'wheelchair-tennis', 'external_name' => 'PARASPORTS_WHEELCHAIR_TENNIS', 'external_label' => 'Wheelchair tennis'],
            ['sport_type' => 'pickleball', 'external_name' => 'PICKLEBALL', 'external_label' => 'Pickleball'],
            ['sport_type' => 'pilates', 'external_name' => 'PILATES', 'external_label' => 'Pilates'],
            ['sport_type' => 'pool-swimming', 'external_name' => 'POOL_SWIMMING', 'external_label' => 'Pool swimming'],
            ['sport_type' => 'riding', 'external_name' => 'RIDING', 'external_label' => 'Riding'],
            ['sport_type' => 'ringette', 'external_name' => 'RINGETTE', 'external_label' => 'Ringette'],
            ['sport_type' => 'road-cycling', 'external_name' => 'ROAD_BIKING', 'external_label' => 'Road cycling'],
            ['sport_type' => 'road-running', 'external_name' => 'ROAD_RUNNING', 'external_label' => 'Road running'],
            ['sport_type' => 'roller-skating', 'external_name' => 'ROLLER_BLADING', 'external_label' => 'Roller skating'],
            ['sport_type' => 'classic-roller-skiing', 'external_name' => 'ROLLER_SKIING_CLASSIC', 'external_label' => 'Classic roller skiing'],
            ['sport_type' => 'freestyle-roller-skiing', 'external_name' => 'ROLLER_SKIING_FREESTYLE', 'external_label' => 'Freestyle roller skiing'],
            ['sport_type' => 'rowing', 'external_name' => 'ROWING', 'external_label' => 'Rowing'],
            ['sport_type' => 'rugby', 'external_name' => 'RUGBY', 'external_label' => 'Rugby'],
            ['sport_type' => 'rucking', 'external_name' => 'RUCKING', 'external_label' => 'Rucking'],
            ['sport_type' => 'running', 'external_name' => 'RUNNING', 'external_label' => 'Running'],
            ['sport_type' => 'show', 'external_name' => 'SHOW_DANCING', 'external_label' => 'Show'],
            ['sport_type' => 'shooting-indoor', 'external_name' => 'SHOOTING_SPORT_INDOOR', 'external_label' => 'Shooting (indoor)'],
            ['sport_type' => 'shooting-outdoor', 'external_name' => 'SHOOTING_SPORT_OUTDOOR', 'external_label' => 'Shooting (outdoor)'],
            ['sport_type' => 'skateboarding', 'external_name' => 'SKATEBOARDING', 'external_label' => 'Skateboarding'],
            ['sport_type' => 'skating', 'external_name' => 'SKATING', 'external_label' => 'Skating'],
            ['sport_type' => 'ski-machine', 'external_name' => 'SKIERG', 'external_label' => 'Ski machine'],
            ['sport_type' => 'snowboarding', 'external_name' => 'SNOWBOARDING', 'external_label' => 'Snowboarding'],
            ['sport_type' => 'snowshoe-trekking', 'external_name' => 'SNOWSHOE_TREKKING', 'external_label' => 'Snowshoe trekking'],
            ['sport_type' => 'soccer', 'external_name' => 'SOCCER', 'external_label' => 'Soccer'],
            ['sport_type' => 'spinning', 'external_name' => 'SPINNING', 'external_label' => 'Spinning'],
            ['sport_type' => 'sup', 'external_name' => 'SUP', 'external_label' => 'SUP'],
            ['sport_type' => 'squash', 'external_name' => 'SQUASH', 'external_label' => 'Squash'],
            ['sport_type' => 'stair-workout', 'external_name' => 'STAIR_WORKOUT', 'external_label' => 'Stair workout'],
            ['sport_type' => 'street', 'external_name' => 'STREET_DANCING', 'external_label' => 'Street'],
            ['sport_type' => 'strength-training', 'external_name' => 'STRENGTH_TRAINING', 'external_label' => 'Strength training'],
            ['sport_type' => 'stretching', 'external_name' => 'STRETCHING', 'external_label' => 'Stretching'],
            ['sport_type' => 'swimming', 'external_name' => 'SWIMMING', 'external_label' => 'Swimming'],
            ['sport_type' => 'table-tennis', 'external_name' => 'TABLE_TENNIS', 'external_label' => 'Table tennis'],
            ['sport_type' => 'taekwondo', 'external_name' => 'TAEKWONDO_MARTIAL_ARTS', 'external_label' => 'Taekwondo'],
            ['sport_type' => 'telemark-skiing', 'external_name' => 'TELEMARK_SKIING', 'external_label' => 'Telemark skiing'],
            ['sport_type' => 'tennis', 'external_name' => 'TENNIS', 'external_label' => 'Tennis'],
            ['sport_type' => 'track-and-field-running', 'external_name' => 'TRACK_AND_FIELD_RUNNING', 'external_label' => 'Track&field running'],
            ['sport_type' => 'trail-running', 'external_name' => 'TRAIL_RUNNING', 'external_label' => 'Trail running'],
            ['sport_type' => 'treadmill-running', 'external_name' => 'TREADMILL_RUNNING', 'external_label' => 'Treadmill running'],
            ['sport_type' => 'triathlon', 'external_name' => 'TRIATHLON', 'external_label' => 'Triathlon'],
            ['sport_type' => 'cycling', 'external_name' => 'TRIATHLON_CYCLING', 'external_label' => 'Cycling'],
            ['sport_type' => 'running', 'external_name' => 'TRIATHLON_RUNNING', 'external_label' => 'Running'],
            ['sport_type' => 'open-water-swimming', 'external_name' => 'TRIATHLON_SWIMMING', 'external_label' => 'Open water swimming'],
            ['sport_type' => 'trotting', 'external_name' => 'TROTTING', 'external_label' => 'Trotting'],
            ['sport_type' => 'ultimate', 'external_name' => 'ULTIMATE', 'external_label' => 'Ultimate'],
            ['sport_type' => 'ultra-running', 'external_name' => 'ULTRARUNNING_RUNNING', 'external_label' => 'Ultra running'],
            ['sport_type' => 'climbing-indoor', 'external_name' => 'VERTICALSPORTS_WALLCLIMBING', 'external_label' => 'Climbing (indoor)'],
            ['sport_type' => 'climbing-outdoor', 'external_name' => 'VERTICALSPORTS_OUTCLIMBING', 'external_label' => 'Climbing (outdoor)'],
            ['sport_type' => 'volleyball', 'external_name' => 'VOLLEYBALL', 'external_label' => 'Volleyball'],
            ['sport_type' => 'walking', 'external_name' => 'WALKING', 'external_label' => 'Walking'],
            ['sport_type' => 'water-sports', 'external_name' => 'WATER_EXERCISE', 'external_label' => 'Water sports'],
            ['sport_type' => 'water-running', 'external_name' => 'WATER_RUNNING', 'external_label' => 'Water running'],
            ['sport_type' => 'canoeing', 'external_name' => 'WATERSPORTS_CANOEING', 'external_label' => 'Canoeing'],
            ['sport_type' => 'kayaking', 'external_name' => 'WATERSPORTS_KAYAKING', 'external_label' => 'Kayaking'],
            ['sport_type' => 'kitesurfing', 'external_name' => 'WATERSPORTS_KITESURFING', 'external_label' => 'Kitesurfing'],
            ['sport_type' => 'sailing', 'external_name' => 'WATERSPORTS_SAILING', 'external_label' => 'Sailing'],
            ['sport_type' => 'surfing', 'external_name' => 'WATERSPORTS_SURFING', 'external_label' => 'Surfing'],
            ['sport_type' => 'wakeboarding', 'external_name' => 'WATERSPORTS_WAKEBOARDING', 'external_label' => 'Wakeboarding'],
            ['sport_type' => 'water-skiing', 'external_name' => 'WATERSPORTS_WATERSKI', 'external_label' => 'Water skiing'],
            ['sport_type' => 'windsurfing', 'external_name' => 'WATERSPORTS_WINDSURFING', 'external_label' => 'Windsurfing'],
            ['sport_type' => 'classic-xc-skiing', 'external_name' => 'XC_SKIING_CLASSIC', 'external_label' => 'Classic XC skiing'],
            ['sport_type' => 'freestyle-xc-skiing', 'external_name' => 'XC_SKIING_FREESTYLE', 'external_label' => 'Freestyle XC skiing'],
            ['sport_type' => 'yoga', 'external_name' => 'YOGA', 'external_label' => 'Yoga'],
    ];

    public function run(): void
    {
        // Assumes a `data_sources` table with a unique `name` column.
        // Adjust the lookup below if your DataSource model differs
        // (e.g. uses a slug column instead).
        $dataSource = DataSource::where('name', 'polar')->firstOrFail();

        foreach ($this->mappings as $mapping) {
            $sportType = SportType::where('name', $mapping['sport_type'])->first();

            if (! $sportType) {
                throw new \RuntimeException(
                    "Unknown sport_type '{$mapping['sport_type']}', run SportTypeSeeder first."
                );
            }

            ExternalSportTypeMapping::firstOrCreate(
                [
                    'data_source_id' => $dataSource->id,
                    'external_name' => $mapping['external_name'],
                ],
                [
                    'sport_type_id' => $sportType->id,
                    'external_label' => $mapping['external_label'],
                ],
            );
        }
    }
}
