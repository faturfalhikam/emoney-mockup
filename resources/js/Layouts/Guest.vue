<template>
  <div
    class="
      min-h-screen
      flex flex-col
      sm:justify-center
      items-center
      pt-6
      sm:pt-0
      bg-gray-100
    "
  >
    <div>
      <Link href="/">
        <BreezeApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
      </Link>
    </div>

    <div class="p-2 bg-red-100 border border-red-600 text-red-600 rounded">
        Failed to check payment status: timeout
    </div>

    <div
      class="
        w-full
        sm:max-w-md
        mt-6
        p-6
        bg-white
        shadow-md
        overflow-hidden
        sm:rounded-lg
      "
    >
      <slot />
    </div>
  </div>
</template>

<script>
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    BreezeApplicationLogo,
    Link,
  },
};
</script>
