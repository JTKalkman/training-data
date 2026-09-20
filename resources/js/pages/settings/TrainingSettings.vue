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
import TrainingSettingController from '@/actions/App/Http/Controllers/Settings/TrainingSettingController';
import { edit } from '@/routes/training-settings';

type Props = {
    'trainingSettings': object | null
};

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Training settings',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;
const trainingSession = page.props.trainingSettings ?? null;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Training settings" />

        <h1 class="sr-only">Training Settings</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    title="Training settings"
                    description="Update your training settings."
                />

                <Form
                    v-bind="TrainingSettingController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >

                    <div class="grid gap-2">
                        <Label for="date_of_birth">Date of birth</Label>
                        <Input
                            id="date_of_birth"
                            class="mt-1 block w-full"
                            name="date_of_birth"
                            :default-value="trainingSession?.date_of_birth ?? null"
                            autocomplete="date of birth"
                            placeholder="Date of birth"
                            type="date"
                        />
                        <InputError class="mt-2" :message="errors.date_of_birth" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="weight_kg">Weight (kg)</Label>
                        <Input
                            id="weight_kg"
                            class="mt-1 block w-full"
                            name="weight_kg"
                            :default-value="trainingSession?.weight_kg ?? null"
                            autocomplete="weight"
                            placeholder="Weight"
                            type="number"
                            step="0.1"
                        />
                        <InputError class="mt-2" :message="errors.weight_kg" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="height_cm">Height (cm)</Label>
                        <Input
                            id="height_cm"
                            class="mt-1 block w-full"
                            name="height_cm"
                            :default-value="trainingSession?.height_cm ?? null"
                            autocomplete="height"
                            placeholder="Height"
                            type="number"
                        />
                        <InputError class="mt-2" :message="errors.height_cm" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="resting_heart_rate">Resting heart rate (bpm)</Label>
                        <Input
                            id="resting_heart_rate"
                            class="mt-1 block w-full"
                            name="resting_heart_rate"
                            :default-value="trainingSession?.resting_heart_rate ?? null"
                            autocomplete="Resting heart rate"
                            placeholder="Resting heart rate"
                            type="number"
                        />
                        <InputError class="mt-2" :message="errors.resting_heart_rate" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="max_heart_rate">Max heart rate (bpm)</Label>
                        <Input
                            id="max_heart_rate"
                            class="mt-1 block w-full"
                            name="max_heart_rate"
                            :default-value="trainingSession?.max_heart_rate ?? null"
                            autocomplete="Max heart rate"
                            placeholder="Max heart rate"
                            type="number"
                        />
                        <InputError class="mt-2" :message="errors.max_heart_rate" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="aerobic_threshold_bpm">Aerobic threshold (bpm)</Label>
                        <Input
                            id="aerobic_threshold_bpm"
                            class="mt-1 block w-full"
                            name="aerobic_threshold_bpm"
                            :default-value="trainingSession?.aerobic_threshold_bpm ?? null"
                            autocomplete="Aerobic threshold"
                            placeholder="Aerobic threshold"
                            type="number"
                        />
                        <InputError class="mt-2" :message="errors.aerobic_threshold_bpm" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="anaerobic_threshold_bpm">Anaerobic threshold (bpm)</Label>
                        <Input
                            id="anaerobic_threshold_bpm"
                            class="mt-1 block w-full"
                            name="anaerobic_threshold_bpm"
                            :default-value="trainingSession?.anaerobic_threshold_bpm ?? null"
                            autocomplete="Anaerobic threshold"
                            placeholder="Anaerobic threshold"
                            type="number"
                        />
                        <InputError class="mt-2" :message="errors.anaerobic_threshold_bpm" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="mas_pace">Maximum aerobic speed (MAS, minute/km)</Label>
                        <Input
                            id="mas_pace"
                            class="mt-1 block w-full"
                            name="mas_pace"
                            :default-value="trainingSession?.mas_pace ?? null"
                            autocomplete="Maximum aerobic speed"
                            placeholder="Maximum aerobic speed"
                            type="text"
                            />
                            <!-- pattern="/^(\d+):([0-5]\d)$/" -->
                        <InputError class="mt-2" :message="errors.mas_pace" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="ftp_watts">Functional threshold power (FTP, watts)</Label>
                        <Input
                            id="ftp_watts"
                            class="mt-1 block w-full"
                            name="ftp_watts"
                            :default-value="trainingSession?.ftp_watts ?? null"
                            autocomplete="Functional threshold power"
                            placeholder="Functional threshold power"
                            type="number"
                        />
                        <InputError class="mt-2" :message="errors.ftp_watts" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="map_watts">Maximum aerobic power (MAP, watts)</Label>
                        <Input
                            id="map_watts"
                            class="mt-1 block w-full"
                            name="map_watts"
                            :default-value="trainingSession?.map_watts ?? null"
                            autocomplete="Maximum aerobic power"
                            placeholder="Maximum aerobic power"
                            type="number"
                        />
                        <InputError class="mt-2" :message="errors.map_watts" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            >Save</Button
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
