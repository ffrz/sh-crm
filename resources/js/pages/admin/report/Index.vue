<script setup>
const title = 'Laporan';

</script>
<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div class="row">
      <q-card class="col q-pa-none q-ma-sm" square flat bordered>
        <q-card-section>
          <div class="text-subtitle2 text-bold">Laporan Interaksi</div>
          <ul class="menu">
            <li><a href="#">Menu 1</a></li>
            <li><a href="#">Menu 2</a></li>
            <li><a href="#">Menu 3</a></li>
            <li><a href="#">Menu 4</a></li>
            <li><a href="#">Menu 5</a></li>
          </ul>
        </q-card-section>

        <q-card-section>
          <div class="text-subtitle2 text-bold">Laporan Closing</div>
          <ul class="menu">
            <li><a href="#">Menu 1</a></li>
            <li><a href="#">Menu 2</a></li>
            <li><a href="#">Menu 3</a></li>
            <li><a href="#">Menu 4</a></li>
            <li><a href="#">Menu 5</a></li>
          </ul>
        </q-card-section>
      </q-card>
    </div>
  </authenticated-layout>
</template>
