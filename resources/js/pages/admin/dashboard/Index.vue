<script setup>
import CurrentStatCards from "./cards/CurrentStatCards.vue";
import StatCards from "./cards/StatCards.vue";
import ChartCard from "./cards/ChartCard.vue";
import TopCard from "./cards/TopCard.vue";
import { router } from "@inertiajs/vue3";

import { ref } from "vue";
import { getQueryParams } from "@/helpers/utils";

const title = "Dashboard";
const showFilter = ref(true);
const selected_period = ref(getQueryParams()["period"] ?? "this_month");

const period_options = ref([
  { value: "today", label: "Hari Ini" },
  { value: "yesterday", label: "Kemarin" },
  { value: "this_week", label: "Minggu Ini" },
  { value: "last_week", label: "Minggu Lalu" },
  { value: "this_month", label: "Bulan Ini" },
  { value: "last_month", label: "Bulan Lalu" },
  { value: "this_year", label: "Tahun Ini" },
  { value: "last_year", label: "Tahun Lalu" },
  { value: "last_7_days", label: "7 Hari Terakhir" },
  { value: "last_30_days", label: "30 Hari Terakhir" },
  { value: "all_time", label: "Seluruh Waktu" },
]);
const onFilterChange = () => {
  router.visit(route("admin.dashboard", { period: selected_period.value }));
};
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #right-button>
      <q-btn class="q-ml-sm" :icon="!showFilter ? 'filter_alt' : 'filter_alt_off'" color="grey" dense
        @click="showFilter = !showFilter" />
    </template>
    <template #header v-if="showFilter">
      <q-toolbar class="filter-bar">
        <div class="row q-col-gutter-xs items-center q-pa-sm full-width">
          <q-select class="custom-select col-12" style="min-width: 150px" v-model="selected_period"
            :options="period_options" label="Bulan" dense map-options emit-value outlined
            @update:model-value="onFilterChange" />
        </div>
      </q-toolbar>
    </template>
    <div class="q-pa-sm">
      <div>
        <div class="text-subtitle1 text-bold text-grey-8">Statistik Aktual</div>
        <current-stat-cards class="q-py-none" />
      </div>
      <div class="q-pt-md">
        <div class="text-subtitle1 text-bold text-grey-8">
          Statistik {{period_options.find((a) => a.value == selected_period).label}}
        </div>
        <stat-cards class="q-py-none" />
      </div>
      <div class="q-pt-sm" v-if="false">
        <div class="row q-col-gutter-sm">
          <div class="col-md-6 col-12">
            <top-card class="full-width full-height" :items="[
              { id: 1, name: 'Alychia', total: 27515000 },
              { id: 2, name: 'Persada', total: 7525000 },
              { id: 3, name: 'G-Fashion', total: 5520000 },
              { id: 4, name: 'Parinda', total: 730000 },
              { id: 5, name: 'Anto', total: 1215000 },
            ]" title="Top 5 Client" route_url="admin.customer.index" />
          </div>
          <div class="col-md-6 col-12">
            <top-card class="full-width full-height" :items="[
              { id: 1, name: 'Deni', total: 122 },
              { id: 2, name: 'Wanda', total: 121 },
              { id: 3, name: 'Jeri', total: 93 },
              { id: 4, name: 'Uden', total: 88 },
              { id: 5, name: 'Dani', total: 60 },
            ]" title="Top 5 Salesman" route_url="admin.user.index" />
          </div>
        </div>
      </div>
      <div v-if="1">
        <chart-card class="q-py-none q-pt-lg" />
      </div>
    </div>
  </authenticated-layout>
</template>
