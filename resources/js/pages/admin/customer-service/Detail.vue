<script setup>
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Layanan Pengguna";

</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <div class="row">
          <q-card square flat bordered class="col">
            <q-card-section>
              <div class="text-subtitle1 text-bold text-grey-8">Info Layanan Pengguna</div>
              <table class="detail">
                <tbody>
                  <tr>
                    <td style="width:150px">Id</td>
                    <td style="width:1px">:</td>
                    <td>#{{ page.props.data.id }}</td>
                  </tr>
                  <tr>
                    <td>Pelanggan</td>
                    <td>:</td>
                    <td>{{ page.props.data.customer.name }} - {{
                      page.props.data.customer.company }} (#{{ page.props.data.customer.id }})
                      <br />{{ page.props.data.customer.address }}
                    </td>
                  </tr>
                  <tr>
                    <td>Layanan</td>
                    <td>:</td>
                    <td>{{ page.props.data.service.name }}</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ $CONSTANTS.CUSTOMER_SERVICE_STATUSES[page.props.data.status] }}</td>
                  </tr>
                  <tr>
                    <td>Tgl Mulai</td>
                    <td>:</td>
                    <td>{{ page.props.data.start_date ? $dayjs(page.props.data.start_date).format('DD MMMM YYYY') : '-'}}</td>
                  </tr>
                  <tr>
                    <td>Tgl Berhenti</td>
                    <td>:</td>
                    <td>{{ page.props.data.end_date ? $dayjs(page.props.data.end_date).format('DD MMMM YYYY') : '-'}}</td>
                  </tr>
                  <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td>{{ page.props.data.notes }}</td>
                  </tr>
                </tbody>
              </table>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-page>
  </authenticated-layout>
</template>
