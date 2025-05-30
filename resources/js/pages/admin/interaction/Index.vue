<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { check_role, getQueryParams } from "@/helpers/utils";
import { useQuasar } from "quasar";

const title = "Visit Pelanggan";
const $q = useQuasar();
const showFilter = ref(false);
const rows = ref([]);
const loading = ref(true);
const filter = reactive({
  search: "",
  status: "all",
  ...getQueryParams(),
});
const statusColors = {
  new: "yellow",
  contacted: "blue",
  cold: "orange",
  hot: "green",
  converted: "grey",
  churned: "red",
  inactive: "black",
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
    name: "date",
    label: "Tanggal",
    field: "date",
    align: "left",
    sortable: true,
  },
  {
    name: "sales",
    label: "Sales",
    field: "sales",
    align: "left",
    sortable: false,
  },
  {
    name: "customer",
    label: "Customer",
    field: "customer",
    align: "left",
    sortable: true,
  },
  {
    name: "purpose",
    label: "Purpose",
    field: "purpose",
    align: "left",
    sortable: true,
  },
  {
    name: "status",
    label: "Status",
    field: "status",
    align: "center",
    sortable: false,
  },
  {
    name: "action",
    align: "right",
  },
];

const statuses = [
  { value: "all", label: "Semua" },
  ...Object.entries(window.CONSTANTS.INTERACTION_STATUSES).map(([key, value]) => ({
    value: key,
    label: value,
  })),
];

onMounted(() => {
  fetchItems();
});

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus visit ${row.name}?`,
    url: route("admin.visit.delete", row.id),
    fetchItemsCallback: fetchItems,
    loading,
  });

const fetchItems = (props = null) => {
  handleFetchItems({
    pagination,
    filter,
    props,
    rows,
    url: route("admin.visit.data"),
    loading,
  });
};

const onFilterChange = () => fetchItems();
const onRowClicked = (row) => router.get(route('admin.visit.detail', { id: row.id }));
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
      <q-btn icon="add" dense color="primary" @click="router.get(route('admin.visit.add'))" />
      <q-btn class="q-ml-sm" :icon="!showFilter ? 'filter_alt' : 'filter_alt_off'" color="grey" dense
        @click="showFilter = !showFilter" />
    </template>
    <template #header v-if="showFilter">
      <q-toolbar class="filter-bar">
        <div class="row q-col-gutter-xs items-center q-pa-sm full-width">
          <q-select class="custom-select col-xs-12 col-sm-2" style="min-width: 150px" v-model="filter.status"
            :options="statuses" label="Status" dense map-options emit-value outlined
            @update:model-value="onFilterChange" />
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
            <q-icon size="2em" name="sentiment_dissatisfied" />
            <span>
              {{ message }}
              {{ filter ? " with term " + filter : "" }}</span>
            <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
          </div>
        </template>

        <template v-slot:body="props">
          <q-tr :props="props" :class="props.row.active == 'inactive' ? 'bg-red-1' : ''" class="cursor-pointer"
            @click="onRowClicked(props.row)">
            <q-td key="id" :props="props" class="wrap-column">
              <div>
                {{ props.row.id }}
                <template v-if="$q.screen.lt.md">
                  - <span><q-icon name="history" /> {{ props.row.visit_date }}</span>
                  <template v-if="props.row.visit_time">
                    <span class="text-grey-6">({{ props.row.visit_time }})</span>
                  </template>
                </template>
              </div>
              <template v-if="$q.screen.lt.md">
                <div><q-icon name="people" /> #{{ props.row.customer.id }} - {{ props.row.customer.name }} - {{ props.row.customer.company }}</div>
                <div v-if="props.row.customer.address"><q-icon name="location_on" />{{ props.row.customer.address }} </div>
                <q-badge :color="statusColors[props.row.status]">{{ $CONSTANTS.VISIT_STATUSES[props.row.status] }}</q-badge>
                <div><q-icon name="input" /> {{ props.row.purpose }}</div>
                <div v-if="props.row.notes"><q-icon name="notes" /> {{ props.row.notes }}</div>
              </template>
            </q-td>
            <q-td key="date" :props="props" class="wrap-column">
              <div>
                {{ $dayjs(props.row.visit_date).format('YYYY-MM-DD') }}
                <template v-if="props.row.visit_time">
                  <span class="text-grey-6">({{ props.row.visit_time }})</span>
                </template>
              </div>
              <div><q-icon name="history" v-if="$q.screen.lt.md" /> {{ props.row.name }}</div>
            </q-td>
            <q-td key="sales" :props="props">
              {{ props.row.user.username }}
            </q-td>
            <q-td key="customer" :props="props">
              #{{ props.row.customer.id }} - {{ props.row.customer.name }}
              <br />{{ props.row.customer.business_type }}
              <br />{{ props.row.customer.address }}
            </q-td>
            <q-td key="purpose" :props="props">
              {{ props.row.user.purpose }}
            </q-td>
            <q-td key="status" :props="props">
              <q-badge :color="statusColors[props.row.status]">{{ $CONSTANTS.VISIT_STATUSES[props.row.status]
                }}</q-badge>
            </q-td>
            <q-td key="action" :props="props">
              <div class="flex justify-end">
                <q-btn :disabled="!check_role($CONSTANTS.USER_ROLE_ADMIN)" icon="more_vert" dense flat
                  style="height: 40px; width: 30px" @click.stop>
                  <q-menu anchor="bottom right" self="top right" transition-show="scale" transition-hide="scale">
                    <q-list style="width: 200px">
                      <!-- <q-item clickable v-ripple v-close-popup
                        @click.stop="router.get(route('admin.visit.duplicate', props.row.id))">
                        <q-item-section avatar>
                          <q-icon name="file_copy" />
                        </q-item-section>
                        <q-item-section icon="copy"> Duplikat </q-item-section>
                      </q-item> -->
                      <q-item clickable v-ripple v-close-popup
                        @click.stop="router.get(route('admin.visit.edit', props.row.id))">
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
