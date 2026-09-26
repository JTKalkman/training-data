<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import PhysicalDataController from '@/actions/App/Http/Controllers/Settings/PhysicalDataController';
import { edit } from '@/routes/physical-data';

type Props = {
  'physicalData': object | null
};

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [{
  title: 'Training settings',
  href: edit().url,
}];

const page = usePage();
const physicalData = page.props.physicalData ?? null;

const sexOptions = [
  { value: 'male', label: 'Male' },
  { value: 'female', label: 'Female' },
  { value: 'prefer_not_to_say', label: 'Prefer not to say' },
];

</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">

    <Head title="Physical Data" />

    <h1 class="sr-only">Physical Data</h1>

    <SettingsLayout>
      <div class="flex flex-col space-y-6">
        
        <Heading
          variant="small"
          title="Physical data"
          description="
            We need some physical data from you to perform our analysis 
            of your training results. This is totally optional and we can fall back
            to defaults or data provided from Polar or Garmin.
          "
        />

        <Form
          v-bind="PhysicalDataController.update.form()"
          class="space-y-6"
          :options="{ preserveScroll: true }"
          v-slot="{ errors, processing, recentlySuccessful }"
        >
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div class="sm:col-span-2">
              <Label class="mb-2" for="date_of_birth">Date of birth</Label>
              <Input
                id="date_of_birth"
                class="mt-1 block w-full"
                name="date_of_birth"
                :default-value="physicalData?.date_of_birth ?? null"
                autocomplete="date of birth"
                placeholder="Date of birth"
                type="date"
              />
              <InputError class="mt-2" :message="errors.date_of_birth" />
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="weight_kg">Weight (kg)</Label>
              <Input
                id="weight_kg"
                class="mt-1 block w-full"
                name="weight_kg"
                :default-value="physicalData?.weight_kg ?? null"
                autocomplete="weight"
                placeholder="Weight"
                type="number"
                step="0.1"
              />
              <InputError class="mt-2" :message="errors.weight_kg" />
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="height_cm">Height (cm)</Label>
              <Input
                id="height_cm"
                class="mt-1 block w-full"
                name="height_cm"
                :default-value="physicalData?.height_cm ?? null"
                autocomplete="height"
                placeholder="Height"
                type="number"
              />
              <InputError class="mt-2" :message="errors.height_cm" />
            </div>

            <div class="sm:col-span-2">
              <p
                class="text-sm leading-none font-medium mb-2"
              >Sex</p>
              <div class="flex flex-wrap gap-x-4">
                <div
                  class="flex flex-nowrap gap-x-2"  
                  v-for="option in sexOptions" 
                  :key="option.value"
                >
                  <Label 
                    class="text-nowrap" 
                    :for="`sex-${option.label}`"
                  >{{ option.label }}</Label>
                  <Input
                    :id="`sex-${option.label}`"
                    class=""
                    name="sex"
                    :default-value="option.value"
                    type="radio"
                    :checked="option.value === physicalData.sex"
                  />
                </div>
              </div>
            </div>

            <div class="sm:col-span-2">
              <p class="text-sm text-muted-foreground">
                We use your age to estimate the default heart rate zones and maximum heart rate.
                Your weight and height aren't used for further analysis for now but this might change
                in the future. 
              </p>
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="resting_heart_rate">Resting heart rate (bpm)</Label>
              <Input
                id="resting_heart_rate"
                class="mt-1 block w-full"
                name="resting_heart_rate"
                :default-value="physicalData?.resting_heart_rate ?? null"
                autocomplete="Resting heart rate"
                placeholder="Resting heart rate"
                type="number"
              />
              <InputError class="mt-2" :message="errors.resting_heart_rate" />
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="max_heart_rate">Max heart rate (bpm)</Label>
              <Input
                id="max_heart_rate"
                class="mt-1 block w-full"
                name="max_heart_rate"
                :default-value="physicalData?.max_heart_rate ?? null"
                autocomplete="Max heart rate"
                placeholder="Max heart rate"
                type="number"
              />
              <InputError class="mt-2" :message="errors.max_heart_rate" />
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="aerobic_threshold_bpm">Aerobic threshold (bpm)</Label>
              <Input
                id="aerobic_threshold_bpm"
                class="mt-1 block w-full"
                name="aerobic_threshold_bpm"
                :default-value="physicalData?.aerobic_threshold_bpm ?? null"
                autocomplete="Aerobic threshold"
                placeholder="Aerobic threshold"
                type="number"
              />
              <InputError class="mt-2" :message="errors.aerobic_threshold_bpm" />
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="anaerobic_threshold_bpm">Anaerobic threshold (bpm)</Label>
              <Input
                  id="anaerobic_threshold_bpm"
                  class="mt-1 block w-full"
                  name="anaerobic_threshold_bpm"
                  :default-value="physicalData?.anaerobic_threshold_bpm ?? null"
                  autocomplete="Anaerobic threshold"
                  placeholder="Anaerobic threshold"
                  type="number"
              />
              <InputError class="mt-2" :message="errors.anaerobic_threshold_bpm" />
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2 gap-x-0" for="vo2_max">VO<sub>2</sub>max</Label>
              <Input
                id="vo2_max"
                class="mt-1 block w-full"
                name="vo2_max"
                :default-value="physicalData?.vo2_max ?? null"
                autocomplete="VO₂max"
                placeholder="VO₂max"
                type="number"
              />
              <InputError class="mt-2" :message="errors.vo2_max" />
            </div>

            <div class="sm:col-span-2">
              <p class="text-sm text-muted-foreground">
                You can use our estimated heart rate or if you have access to more accurate
                measures you can enter these here.
              </p>
            </div>

            <div class="sm:col-span-2 flex justify-end">
              <Button
                type="button"
                variant="secondary"
              >Estimate heart rate and VO2 max</Button>
            </div>

            <!-- Running -->
            <div class="md:col-span-2">
              <h3 class="text-base font-medium">Running</h3>
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="mas_pace">Maximum aerobic speed (MAS, minute/km)</Label>
              <Input
                id="mas_pace"
                class="mt-1 block w-full"
                name="mas_pace"
                :default-value="physicalData?.mas_pace ?? null"
                autocomplete="Maximum aerobic speed"
                placeholder="Maximum aerobic speed"
                type="text"
              />
              <InputError class="mt-2" :message="errors.mas_pace" />
            </div>
            <!-- pattern="/^(\d+):([0-5]\d)$/" -->

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="map_watts">Maximum aerobic power (MAP, watts)</Label>
              <Input
              id="map_watts"
              class="mt-1 block w-full"
              name="map_watts"
              :default-value="physicalData?.map_watts ?? null"
              autocomplete="Maximum aerobic power"
              placeholder="Maximum aerobic power"
              type="number"
              />
              <InputError class="mt-2" :message="errors.map_watts" />
            </div>

            <div class="sm:col-span-2">
              <p class="text-sm text-muted-foreground">
                This is the running pace intensity you can sustain for a few minutes only. This is 
                determined by your VO<sub class="">2</sub> max. Another more scientific term is vVO<sub>2</sub>max.
              </p>
            </div>

            <div class="sm:col-span-2 flex justify-end">
              <Button
                type="button"
                variant="secondary"
              >Estimated MAS and MAP</Button>
            </div>

            <!-- Cycling -->
            <div class="md:col-span-2">
              <h3 class="text-base font-medium">Cycling</h3>
            </div>

            <div class="flex flex-col justify-between">
              <Label class="mb-2" for="ftp_watts">Functional threshold power (FTP, watts)</Label>
              <Input
                id="ftp_watts"
                class="mt-1 block w-full"
                name="ftp_watts"
                :default-value="physicalData?.ftp_watts ?? null"
                autocomplete="Functional threshold power"
                placeholder="Functional threshold power"
                type="number"
              />
              <InputError class="mt-2" :message="errors.ftp_watts" />
            </div>

            <div class="sm:col-span-2">
              <p class="text-sm text-muted-foreground">
                To analyze your cycling training, we need your functional threshold power (FTP), roughly 
                the power you could sustain for about an hour. This requires a power meter on your bike; 
                without one, there won't be any power data to analyze.
              </p>
            </div>

            <div class="sm:col-span-2 flex justify-end">
              <Button
                type="button"
                variant="secondary"
              >Estimated FTP</Button>
            </div>

            <div class="sm:col-span-2">
              <Button
                class="w-full"
                :disabled="processing"
              >Save</Button>

              <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
              >
                <p
                  v-show="recentlySuccessful"
                  class="text-sm text-neutral-600 w-full text-center mt-4"
                >
                  Saved
                </p>
              </Transition>
            </div>

          </div>    
        </Form>
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
