<template>
  <GuestLayout>
      <h1 class="text-lg font-bold">{{ product.name }}</h1>
      <h2>Rp: {{ product.price }}</h2>
      <hr class="my-2">
      <div class="py-2 mb-5">
          <span class="mb-1 block">Enter your phone number</span>
          <input type="tel" class="w-full rounded-sm border-gray-300" placeholder="08xxxxxxxxx" v-model="phone" />
          <div class="bg-red-100 my-3 border-red-500 border rounded-sm text-red-600 p-3" v-if="error != ''">
              {{ error.rd }}
          </div>
          <div class="mt-3 py-3 border-t">
              <h3 class="font-bold">Instructions</h3>
              <ul class="list-disc px-4 text-gray-700">
                  <!-- <li>Buka aplikasi OVO kamu untuk melakukan pembayaran.</li>
                  <li>Pastikan segera melakukan pembayaran untuk menghindari pembatalan order secara otomatis</li> -->
                  <!-- After click "Bayar", open your OVO application notification / Open your OVO app, if the confirmation page isn’t there please click the bell icon on the upper right to go to your OVO confirmation page to pay your bill in <merchant app name> -->

                  <!-- <li>Enter your registered phone number in the OVO application with the 08xxx format.</li> -->
                  <li>After click "Pay", open your OVO application notification / Open your OVO app, if the confirmation page isn’t there please click the bell icon on the upper right to go to your OVO confirmation page to pay your bill in WinPay</li>
                  <li>Review your payment confirmation in the OVO app and please pay immediately (under 55s) to avoid transaction time out with click “Pay”</li>
                  <li>You can choose OVO Cash or OVO Points.</li>
              </ul>
          </div>
      </div>
      <div>
          <Link class="bg-indigo-600 block text-center text-white rounded p-3"
            @click="handleClickPayWithOVO"
          >
            Pay
          </Link>
      </div>
  </GuestLayout>
</template>

<script>
import GuestLayout from '@/Layouts/Guest'
import {Link} from '@inertiajs/inertia-vue3'
import { reactive, toRefs } from '@vue/reactivity'
import { Inertia } from '@inertiajs/inertia'
import { onMounted } from '@vue/runtime-core'
export default {
    components:{
        GuestLayout,
        Link
    },
    props: ['product', 'slug', 'error'],
    setup(props){

        const state = reactive({
            phone: '',
        })

        const handleClickPayWithOVO = () => {
            if(state.phone == ''){
                alert('please enter a phone number')
                return
            }

            Inertia.post(route('checkout.payDo', props.slug), {
                phone: state.phone
            }, {
                replace: true,
                preserveState: true
            })
        }

        return {
            ...toRefs(state),
            handleClickPayWithOVO
        }

    }
}
</script>

<style>

</style>
