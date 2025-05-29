<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import { scrollToFirstErrorField } from "@/helpers/utils";
import { useCustomerFilter } from "@/helpers/useCustomerFilter";
import DatePicker from "@/components/DatePicker.vue";
import dayjs from "dayjs";

const page = usePage();
const title = (!!page.props.data.id ? "Edit" : "Tambah") + " Visit";

const { filteredCustomers, filterCustomerFn } = useCustomerFilter(page.props.customers);

const statuses = Object.entries(window.CONSTANTS.VISIT_STATUSES).map(([value, label]) => ({
  label,
  value
}));

const customer_statuses = Object.entries(window.CONSTANTS.CUSTOMER_STATUSES).map(([value, label]) => ({
  label,
  value
}));

const users = page.props.users.map(user => ({
  value: user.id,
  label: `${user.name} (${user.username})`,
}));

const form = useForm({
  id: page.props.data.id,
  user_id: page.props.data.user_id ? Number(page.props.data.user_id) : null,
  customer_id: page.props.data.customer_id ? Number(page.props.data.customer_id) : null,
  visit_date: dayjs(page.props.data.visit_date).format('YYYY-MM-DD'),
  visit_time: page.props.data.visit_time,
  purpose: page.props.data.purpose,
  notes: page.props.data.notes,
  status: page.props.data.status,
  customer_status: page.props.data.customer_status,
  next_followup_date: page.props.data.next_followup_date,
  location: page.props.data.location,
});

const submit = () =>
  handleSubmit({ form, url: route('admin.visit.save') });

</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <q-form class="row" @submit.prevent="submit" @validation-error="scrollToFirstErrorField">
          <q-card square flat bordered class="col">
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-select v-model="form.user_id" label="Sales" :options="users" map-options emit-value
                :error="!!form.errors.user_id" :disable="form.processing" />
              <date-picker v-model="form.visit_date" label="Tanggal Visit" :error="!!form.errors.due_date"
                :disable="form.processing" :error-message="form.errors.visit_date" />
              <q-select v-model="form.customer_id" label="Pelanggan" use-input input-debounce="300" clearable
                :options="filteredCustomers" map-options emit-value @filter="filterCustomerFn" option-label="label"
                :display-value="selectedCustomerLabel" option-value="value" :error="!!form.errors.customer_id"
                :error-message="form.errors.customer_id" :disable="form.processing">
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section>Pelanggan tidak ditemukan</q-item-section>
                  </q-item>
                </template>
              </q-select>
              <q-input v-model.trim="form.purpose" type="text" label="Tujuan Visit" lazy-rules
                :disable="form.processing" :error="!!form.errors.purpose" :error-message="form.errors.purpose" :rules="[
                  (val) => (val && val.length > 0) || 'Tujuan harus diisi.',
                ]" />
              <q-select v-model="form.status" label="Status Visit" :options="statuses" map-options emit-value
                :error-message="form.errors.status" :error="!!form.errors.status" :disable="form.processing" />
              <q-select v-if="form.status == 'done'" v-model="form.customer_status" label="Update Customer Status"
                :options="customer_statuses" map-options emit-value :error="!!form.errors.customer_status"
                :error-message="form.errors.customer_status" :disable="form.processing" />
              <q-input v-model.trim="form.notes" type="textarea" autogrow counter maxlength="255" label="Catatan"
                lazy-rules :disable="form.processing" :error="!!form.errors.notes" :error-message="form.errors.notes"
                :rules="[]" />
              <date-picker v-model="form.next_followup_date" label="Next Follow Up Date"
                :error="!!form.errors.next_followup_date" :disable="form.processing"
                :error-message="form.errors.next_followup_date" />
              <q-input v-model.trim="form.location" type="text" label="Lokasi" lazy-rules :disable="form.processing"
                :error="!!form.errors.location" :error-message="form.errors.location" :rules="[]" />
            </q-card-section>
            <q-card-section class="q-gutter-sm">
              <q-btn icon="save" type="submit" label="Simpan" color="primary" :disable="form.processing" />
              <q-btn icon="cancel" label="Batal" :disable="form.processing"
                @click="router.get(route('admin.visit.index'))" />
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </q-page>

  </authenticated-layout>
</template>
