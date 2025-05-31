<script setup>
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Interaksi";

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
              <div class="text-subtitle1 text-bold text-grey-8">Info Interaksi</div>
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
                    <td>Jenis</td>
                    <td>:</td>
                    <td>{{ $CONSTANTS.INTERACTION_TYPES[page.props.data.type] }}</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ $CONSTANTS.INTERACTION_STATUSES[page.props.data.status] }}</td>
                  </tr>
                  <tr>
                    <td>Sales</td>
                    <td>:</td>
                    <td>{{ page.props.data.user.name }} ({{ page.props.data.user.username }})</td>
                  </tr>
                  <tr>
                    <td>Client</td>
                    <td>:</td>
                    <td>{{ page.props.data.customer.name }} - {{
                      page.props.data.customer.company }} (#{{ page.props.data.customer.id }})
                      <br />{{ page.props.data.customer.address }}
                    </td>
                  </tr>
                  <tr>
                    <td>Service</td>
                    <td>:</td>
                    <td>{{ page.props.data.service.name }}</td>
                  </tr>
                  <tr>
                    <td>Subject</td>
                    <td>:</td>
                    <td>{{ page.props.data.subject }}</td>
                  </tr>
                  <tr>
                    <td>Engagement Level</td>
                    <td>:</td>
                    <td>{{ $CONSTANTS.INTERACTION_ENGAGEMENT_LEVELS[page.props.data.engagement_level] }}</td>
                  </tr>
                  <tr>
                    <td>Summary</td>
                    <td>:</td>
                    <td>{{ page.props.data.summary }}</td>
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
