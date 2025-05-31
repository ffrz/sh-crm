<script setup>
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Client";

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
              <div class="text-subtitle1 text-bold text-grey-8">Info Client</div>
              <table class="detail">
                <tbody>
                  <tr>
                    <td style="width:100px">Nama</td>
                    <td style="width:1px">:</td>
                    <td>{{ page.props.data.name }}</td>
                  </tr>
                  <tr>
                    <td>Perusahaan</td>
                    <td>:</td>
                    <td>{{ page.props.data.company }}</td>
                  </tr>
                  <tr>
                    <td>Jenis Usaha</td>
                    <td>:</td>
                    <td>{{ page.props.data.business_type }}</td>
                  </tr>
                  <tr>
                    <td>No Telepon</td>
                    <td>:</td>
                    <td>{{ page.props.data.phone }}</td>
                  </tr>
                  <tr v-if="page.props.data.email">
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ page.props.data.email }}</td>
                  </tr>
                  <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ page.props.data.address }}</td>
                  </tr>
                  <tr>
                    <td>Sumber</td>
                    <td>:</td>
                    <td>{{ page.props.data.source }}</td>
                  </tr>
                  <tr>
                    <td>Assigned to</td>
                    <td>:</td>
                    <td v-if="page.props.data.assigned_user">{{ page.props.data.assigned_user.name + ' - ' +
                      (page.props.data.assigned_user.username) }}</td>
                    <td v-else>-</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ page.props.data.active ? 'Aktif' : 'Tidak Aktif' }}</td>
                  </tr>
                  <tr>
                    <td>Layanan</td>
                    <td>:</td>
                    <td>
                    </td>
                  </tr>
                  <tr v-if="page.props.data.created_datetime">
                    <td>Ditambahkan</td>
                    <td>:</td>
                    <td>{{ $dayjs(page.props.data.created_datetime).fromNow() }}
                      - {{ $dayjs(page.props.data.created_datetime).format('DD MMMM YYYY HH:mm:ss') }}
                      <tempalte v-if="page.props.data.created_by_user">
                        - {{ page.props.data.created_by_user?.username }}
                      </tempalte>

                    </td>
                  </tr>
                  <tr v-if="page.props.data.updated_datetime">
                    <td>Diperbarui</td>
                    <td>:</td>
                    <td>{{ $dayjs(page.props.data.updated_datetime).fromNow() }}
                      - {{ $dayjs(page.props.data.updated_datetime).format('DD MMMM YYYY HH:mm:ss') }}
                      <tempalte v-if="page.props.data.updated_by_user">
                        - {{ page.props.data.updated_by_user?.username }}
                      </tempalte>
                    </td>
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
