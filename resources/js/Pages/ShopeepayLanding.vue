<template>
  <GuestLayout>
    <img :src="imageurl" class="mb-2 rounded-lg" />
    <span class="text-sm">Invoice: {{ invoice.id }}</span>
    <h1 class="text-lg font-bold mt-3">{{ invoice.product.name }}</h1>
    <h2>Rp: {{ invoice.product.price }}</h2>
    <hr class="my-2" />
    <div class="py-5 mb-5">
      <h3 class="text-lg font-bold mb-5 text-center mb-5">Pay with Shopee:</h3>
      <div class="flex flex-col gap-3">
        <a
          :href="invoice.response.additional_data.url_app"
          class="
            rounded
            p-2
            shopee
            flex
            items-center
            justify-center
            text-white
            font-bold
          "
        >
          Open Shopee App
        </a>
        <div class="text-center text-gray-400">or</div>
        <a
          :href="invoice.response.additional_data.url_web"
          class="
            rounded
            p-2
            shopee-border shopee-text
            flex
            items-center
            justify-center
            text-white
          "
        >
          Pay via Web
        </a>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-3"></div>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/Guest";
import { Link } from "@inertiajs/inertia-vue3";

defineProps(["invoice"]);
</script>
