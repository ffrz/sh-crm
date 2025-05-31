<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { formatNumber } from '@/helpers/utils';

</script>

<template>
  <div class="row">
    <q-card class="bg-transparent no-shadow no-border col" bordered>
      <q-card-section class="q-pa-none">
        <div class="row q-col-gutter-sm ">
          <div class="col-md-4 col-sm-6 col-xs-12">
            <q-item :style="`background-color: #fd8e24`" class="q-pa-none" clickable
              @click="router.visit(route('admin.customer.index'), { status: 'active' })">
              <q-item-section side :style="`background-color: #fd7e14`" class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-filled" name="info" color="white" size="24px" />
              </q-item-section>
              <q-item-section class=" q-pa-md q-ml-none  text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">
                  {{ $page.props.data.active_customer_count }}
                </q-item-label>
                <q-item-label>Client Aktif</q-item-label>
              </q-item-section>
            </q-item>
          </div>
          <div class="col-md-4 col-sm-6 col-xs-12">
            <q-item :style="`background-color: #dc3545`" class="q-pa-none" clickable
              @click="router.visit(route('admin.user.index'), { status: 'active', role: 'sales' })">
              <q-item-section side :style="`background-color: #cc2535`" class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-filled" name="info" color="white" size="24px" />
              </q-item-section>
              <q-item-section class=" q-pa-md q-ml-none  text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">
                  {{ $page.props.data.active_sales_count }}
                </q-item-label>
                <q-item-label>Sales Aktif</q-item-label>
              </q-item-section>
            </q-item>
          </div>
          <div class="col-md-4 col-sm-6 col-xs-12">
            <q-item :style="`background-color: #28a745`" class="q-pa-none" clickable
              @click="router.visit(route('admin.service.index'), { status: 'active' })">
              <q-item-section side :style="`background-color: #189735`" class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-filled" name="info" color="white" size="24px" />
              </q-item-section>
              <q-item-section class=" q-pa-md q-ml-none  text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">
                  {{ $page.props.data.active_service_count }}
                </q-item-label>
                <q-item-label>Layanan Aktif</q-item-label>
              </q-item-section>
            </q-item>
          </div>
          <div class="col-md-4 col-xs-12">
            <q-item :style="`background-color: #e83e8c`" class="q-pa-none" clickable
              @click="router.visit(route('admin.interaction.index'), { status: 'plan' })">
              <q-item-section side :style="`background-color: #d82e7c`" class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-filled" name="info" color="white" size="24px" />
              </q-item-section>
              <q-item-section class=" q-pa-md q-ml-none  text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">
                  {{ formatNumber($page.props.data.active_interaction_plan_count) }}
                </q-item-label>
                <q-item-label>Plan Interaksi Aktif</q-item-label>
              </q-item-section>
            </q-item>
          </div>
          <div class="col-md-4 col-xs-12">
            <q-item :style="`background-color: #e83e8c`" class="q-pa-none" clickable
              @click="router.visit(route('admin.customer-service.index'), { status: 'active', })">
              <q-item-section side :style="`background-color: #d82e7c`" class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-filled" name="info" color="white" size="24px" />
              </q-item-section>
              <q-item-section class=" q-pa-md q-ml-none  text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">
                  {{ formatNumber($page.props.data.active_customer_service_count) }}
                </q-item-label>
                <q-item-label>Layanan Client Aktif</q-item-label>
              </q-item-section>
            </q-item>
          </div>
          
          <div class="col-md-4 col-xs-12">
            <q-item :style="`background-color: #008cff`" class="q-pa-none" clickable
              @click="$inertia.visit(route('admin.user.index', { status: 'active' }))">
              <q-item-section side :style="`background-color: #007bff`" class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-filled" name="groups_2" color="white" size="24px" />
              </q-item-section>
              <q-item-section class=" q-pa-md q-ml-none  text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">
                  {{ $page.props.data.active_user_count }}
                </q-item-label>
                <q-item-label>Pengguna Aktif</q-item-label>
              </q-item-section>
            </q-item>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>
