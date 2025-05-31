<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { check_role, getQueryParams } from "@/helpers/utils";
import { useQuasar } from "quasar";

const title = "Layanan Client";
const $q = useQuasar();
const showFilter = ref(false);
const rows = ref([]);
const loading = ref(true);
const filter = reactive({
  search: "",
  status: "all",
  ...getQueryParams(),
});

const status_colors = {
  active: "green",
  churned: "red",
  lost: "black",
};

const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "id",
  descending: true,
});

const columns = [
  {
    name: "id",
    label: "#",
    field: "id",
    align: "left",
    sortable: true,
  },
  {
    name: "customer",
    label: "Client",
    field: "customer",
    align: "left",
    sortable: false,
  },
  {
    name: "service",
    label: "Layanan",
    field: "service",
    align: "left",
    sortable: false,
  },
  {
    name: "status",
    label: "Status",
    field: "status",
    align: "center",
    sortable: false,
  },
  {
    name: "start_date",
    label: "Tgl Mulai",
    field: "start_date",
    align: "left",
    sortable: true,
  },
  {
    name: "end_date",
    label: "Tgl Berhenti",
    field: "end_date",
    align: "left",
    sortable: true,
  },
  {
    name: "action",
    align: "right",
  },
];

const statuses = [
  { value: "all", label: "Semua" },
  ...Object.entries(window.CONSTANTS.CUSTOMER_SERVICE_STATUSES).map(([key, value]) => ({
    value: key,
    label: value,
  })),
];

onMounted(() => {
  fetchItems();
});

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus layanan client #${row.id}?`,
    url: route("admin.customer-service.delete", row.id),
    fetchItemsCallback: fetchItems,
    loading,
  });

const fetchItems = (props = null) => {
  handleFetchItems({
    pagination,
    filter,
    props,
    rows,
    url: route("admin.customer-service.data"),
    loading,
  });
};

const onFilterChange = () => fetchItems();
const onRowClicked = (row) => router.get(route('admin.customer-service.detail', { id: row.id }));
const computedColumns = computed(() => {
  if ($q.screen.gt.sm) return columns;
  return columns.filter((col) => col.name === "id" || col.name === "action");
});
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #right-button>
      <q-btn icon="add" dense color="primary" @click="router.get(route('admin.customer-service.add'))" />
      <q-btn class="q-ml-sm" :icon="!showFilter ? 'filter_alt' : 'filter_alt_off'" color="grey" dense
        @click="showFilter = !showFilter" />
    </template>
    <template #header v-if="showFilter">
      <q-toolbar class="filter-bar">
        <div class="row q-col-gutter-xs items-center q-pa-sm full-width">
          <q-select class="custom-select col-xs-12 col-sm-2" style="min-width: 150px" v-model="filter.status"
            :options="statuses" label="Status" dense map-options emit-value outlined
            @update:model-value="onFilterChange" />
          <q-select class="custom-select col-xs-12 col-sm-2" style="min-width: 150px" v-model="filter.engagement_level"
            :options="engagement_levels" label="Engagement Level" dense map-options emit-value outlined
            @update:model-value="onFilterChange" />
          <q-select class="custom-select col-xs-12 col-sm-2" style="min-width: 150px" v-model="filter.type"
            :options="types" label="Type" dense map-options emit-value outlined @update:model-value="onFilterChange" />
          <q-input class="col" outlined dense debounce="300" v-model="filter.search" placeholder="Cari" clearable>
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </div>
      </q-toolbar>
    </template>
    <div class="q-pa-sm">
      <q-table class="full-height-table" ref="tableRef" flat bordered square color="primary" row-key="id" virtual-scroll
        v-model:pagination="pagination" :filter="filter.search" :loading="loading" :columns="computedColumns"
        :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
        <template v-slot:loading>
          <q-inner-loading showing color="red" />
        </template>

        <template v-slot:no-data="{ icon, message, filter }">
          <div class="full-width row flex-center text-grey-8 q-gutter-sm">
            <span>
              {{ message }}
              {{ filter ? " with term " + filter : "" }}
            </span>
          </div>
        </template>

        <template v-slot:body="props">
          <q-tr :props="props" :class="props.row.active == 'inactive' ? 'bg-red-1' : ''" class="cursor-pointer"
            @click="onRowClicked(props.row)">
            <q-td key="id" :props="props" class="wrap-column">
              <div>{{ props.row.id }}</div>
              <template v-if="$q.screen.lt.md">
                <div>
                  <q-icon name="people" /> #{{ props.row.customer.id }} - {{ props.row.customer.name }}
                  - {{ props.row.customer.company }}
                </div>
                <div><q-icon name="apps" /> {{ props.row.service.name }}</div>
                <div class="flex items-center q-gutter-sm">
                  <q-badge :color="status_colors[props.row.status]">
                    {{ $CONSTANTS.CUSTOMER_SERVICE_STATUSES[props.row.status] }}
                  </q-badge>
                </div>
                <div v-if="props.row.notes"><q-icon name="notes" /> {{ props.row.notes }}</div>
              </template>
            </q-td>
            <q-td key="customer" :props="props">
              {{ props.row.customer.name }} - {{ props.row.customer.company }} (#{{ props.row.customer.id }})
              <br />{{ props.row.customer.business_type }}
              <br />{{ props.row.customer.address }}
            </q-td>
            <q-td key="service" :props="props">
              {{ props.row.service.name }}
            </q-td>
            <q-td key="status" :props="props">
              <q-badge :color="status_colors[props.row.status]">
                {{ $CONSTANTS.CUSTOMER_SERVICE_STATUSES[props.row.status] }}
              </q-badge>
            </q-td>
            <q-td key="start_date" :props="props">
              {{ props.row.start_date }}
            </q-td>
            <q-td key="end_date" :props="props">
              {{ props.row.end_date }}
            </q-td>
            <q-td key="action" :props="props">
              <div class="flex justify-end">
                <q-btn :disabled="!check_role($CONSTANTS.USER_ROLE_ADMIN)" icon="more_vert" dense flat
                  style="height: 40px; width: 30px" @click.stop>
                  <q-menu anchor="bottom right" self="top right" transition-show="scale" transition-hide="scale">
                    <q-list style="width: 200px">
                      <q-item clickable v-ripple v-close-popup
                        @click.stop="router.get(route('admin.customer-service.edit', props.row.id))">
                        <q-item-section avatar>
                          <q-icon name="edit" />
                        </q-item-section>
                        <q-item-section icon="edit">Edit</q-item-section>
                      </q-item>
                      <q-item @click.stop="deleteItem(props.row)" clickable v-ripple v-close-popup>
                        <q-item-section avatar>
                          <q-icon name="delete_forever" />
                        </q-item-section>
                        <q-item-section>Hapus</q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
              </div>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </div>
  </authenticated-layout>
</template>
