<script setup>
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Visit Pelanggan";

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
              <div class="text-subtitle1 text-bold text-grey-8">Info Visit</div>
              <table class="detail">
                <tbody>
                  <tr>
                    <td style="width:150px">Id</td>
                    <td style="width:1px">:</td>
                    <td>#{{ page.props.data.id }}</td>
                  </tr>
                  <tr>
                    <td>Tgl Visit</td>
                    <td>:</td>
                    <td>{{ $dayjs(page.props.data.visit_date).format('DD MMMM YYYY') }} {{ page.props.data.visit_time }}</td>
                  </tr>
                  <tr>
                    <td>Sales</td>
                    <td>:</td>
                    <td>{{ page.props.data.user.name }} ({{ page.props.data.user.username }})</td> 
                  </tr>
                  <tr>
                    <td>Customer</td>
                    <td>:</td>
                    <td>#{{ page.props.data.customer.id }} - {{ page.props.data.customer.name }} - {{
                      page.props.data.customer.company }}
                      <br />{{ page.props.data.customer.address }}
                    </td>
                  </tr>
                  <tr>
                    <td>Tujuan</td>
                    <td>:</td>
                    <td>{{ page.props.data.purpose }}</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ $CONSTANTS.VISIT_STATUSES[page.props.data.status] }}</td>
                  </tr>
                  <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td>{{ page.props.data.notes }}</td>
                  </tr>
                  <tr>
                    <td>Next Follow Up Date</td>
                    <td>:</td>
                    <td>{{ page.props.data.next_followup_date ? $dayjs(page.props.data.next_followup_date).format('DD MMMM YYYY') : 'Tidak ditetapkan' }}</td>
                  </tr>
                  <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td>{{ page.props.data.location }}</td>
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
