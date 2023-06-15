<template>
  <GuestLayout>
    <span class="text-sm">Invoice: {{ invoice.id }}</span>
    <h1 class="text-lg font-bold mt-3">{{ invoice.product.name }}</h1>
    <h2>Rp: {{ invoice.product.price }}</h2>
    <hr class="my-2" />
    <div class="py-5 mb-5">
      <h3 class="text-lg font-bold mb-5 text-center mb-5">Pay:</h3>
      <div class="flex flex-col gap-1">
        <a
          :href="invoice.response.additional_data.url_web"
          class="rounded p-2 dana flex items-center justify-center text-white font-bold"
        >
          Open Dana App
        </a>
        <!-- <div class="text-center text-gray-400">or</div> -->
        <!-- <a
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
        </a> -->
      </div>
      <div>
        <span class="text-sm text-gray-400">
          You will be redirected to Dana App to complete the payment.
        </span>
        <div class="text-center mt-12">
          <button
            class="btn btn-primary text-red-700 hover:text-red-500"
            @click="handleCancelInvoice"
          >
            Cancel Invoice
          </button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-3"></div>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/Guest";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps(["invoice"]);

const handleCancelInvoice = () => {
  Inertia.delete(route("invoice.delete", props.invoice.id));
};
</script>
