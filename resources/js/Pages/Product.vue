<template>
  <GuestLayout>
    <img :src="imageurl" class="mb-2 rounded-lg" />
    <h1 class="text-lg font-bold">{{ product.name }}</h1>
    <h2>Rp: {{ product.price }}</h2>
    <hr class="my-2" />
    <div class="py-2 mb-5">
      {{ product.description }}
    </div>
    <div>
      <Link
        :href="route('checkout.pay', slug)"
        class="bg-indigo-600 block text-center text-white rounded p-3"
      >
        Checkout
      </Link>
    </div>
  </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/Guest";
import { Link } from "@inertiajs/inertia-vue3";
export default {
  components: {
    GuestLayout,
    Link,
  },
  props: ["product", "slug"],
  setup(props) {
    return {
      imageurl:
        "https://placem.at/things?w=500&h=300&txt=" + props.product.name,
    };
  },
};
</script>

<style>
</style>
