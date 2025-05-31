<script setup>
import {  usePage } from "@inertiajs/vue3";
import { formatNumber } from "@/helpers/utils";

const page = usePage();
const title = "Rincian Closing";

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
              <div class="text-subtitle1 text-bold text-grey-8">Info Closing</div>
              <table class="detail">
                <tbody>
                  <tr>
                    <td style="width:150px">Id</td>
                    <td style="width:1px">:</td>
                    <td>#{{ page.props.data.id }}</td>
                  </tr>
                  <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{ $dayjs(page.props.data.date).format('DD MMMM YYYY') }}</td>
                  </tr>
                  <tr>
                    <td>Sales</td>
                    <td>:</td>
                    <td>{{ page.props.data.user.name }} ({{ page.props.data.user.username }})</td>
                  </tr>
                  <tr>
                    <td>Client</td>
                    <td>:</td>
                    <td>
                      {{ page.props.data.customer.name }}
                      {{ page.props.data.customer.company ? `- ${page.props.data.customer.company}` : '' }}
                      - (#{{ page.props.data.customer.id }})
                      <template v-if="page.props.data.customer.address">
                        <br />{{ page.props.data.customer.address }}
                      </template>
                    </td>
                  </tr>
                  <tr>
                    <td>Layanan</td>
                    <td>:</td>
                    <td>{{ page.props.data.service.name }}</td>
                  </tr>
                  <tr>
                    <td>Deskripsi</td>
                    <td>:</td>
                    <td>{{ page.props.data.description }}</td>
                  </tr>
                  <tr>
                    <td>Jumlah</td>
                    <td>:</td>
                    <td>Rp. {{ formatNumber(page.props.data.amount) }}</td>
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
