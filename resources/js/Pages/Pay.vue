<template>
  <GuestLayout>
    <img :src="imageurl" class="mb-2 rounded-lg" />
    <div class="text-sm mb-1">SKU: {{ slug }}</div>
    <h1 class="text-lg font-bold">{{ product.name }}</h1>
    <h2>Rp: {{ product.price }}</h2>
    <hr class="my-2" />
    <div class="py-5 mb-5">
      <h3 class="text-lg font-bold mb-5">Select Payment Method:</h3>
      <div class="flex flex-col gap-3">
        <Link
          class="rounded p-2 shopee flex items-center justify-center"
          @click="handleClickPayWithShopeepay"
        >
          <img src="@/images/Shopeepay_Logo_White_Horizontal.png" class="h-6" />
        </Link>
        <Link
          class="bg-indigo-600 rounded p-2 flex items-center justify-center"
          @click="handleClickPayWithOVO"
        >
          <img src="@/images/ovo.png" class="h-6" />
        </Link>

        <Link
          class="bg-gray-600 rounded p-2 flex items-center justify-center text-white"
          @click="handleClickPayWithSpeedcash"
        >
          Speedcash
        </Link>
        <Link
          class="shadow-md rounded p-2 flex items-center justify-center"
          @click="handleClickPayWithDana"
        >
          <img src="@/images/Dana_logo.png" class="h-6" />
        </Link>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-3"></div>
  </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/Guest";
import { Link } from "@inertiajs/inertia-vue3";
import { reactive, toRefs } from "@vue/reactivity";
import { Inertia } from "@inertiajs/inertia";
export default {
  components: {
    GuestLayout,
    Link,
  },
  props: ["product", "slug", "error"],
  setup(props) {
    const state = reactive({
      phone: "",
    });

    const handleClickPayWithOVO = () => {
      if (state.phone == "") {
        alert("please enter a phone number");
        return;
      }

      Inertia.post(
        route("checkout.pay.ovo", props.slug),
        {
          phone: state.phone,
        },
        {
          replace: true,
          preserveState: true,
        }
      );
    };
    const handleClickPayWithDana = () => {
        Inertia.post(
        route("checkout.pay.dana", props.slug),
        {
          phone: state.phone,
        },
        {
          replace: true,
          preserveState: true,
        }
      );
    }

    const handleClickPayWithShopeepay = () => {
      Inertia.post(
        route("checkout.pay.shopeepay", props.slug),
        {
          phone: state.phone,
        },
        {
          replace: true,
          preserveState: true,
        }
      );
    };

    const handleClickPayWithSpeedcash = () => {
      Inertia.post(
        route("checkout.pay.speedcash", props.slug),
        {
          phone: state.phone,
        },
        {
          replace: true,
          preserveState: true,
        }
      );
    };

    return {
      ...toRefs(state),
      imageurl:
        "https://picsum.photos/500/300?txt=" + props.product.name,
      handleClickPayWithOVO,
      handleClickPayWithShopeepay,
      handleClickPayWithSpeedcash,
      handleClickPayWithDana
    };
  },
};
</script>
