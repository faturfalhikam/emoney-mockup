<template>
  <GuestLayout>
      <h1 class="text-lg font-bold">{{ product.name }}</h1>
      <h2>Rp: {{ product.price }}</h2>
      <hr class="my-2">
      <div class="py-2 mb-5">
          <div>
              <div class="mb-2 pb-2 border-b text-center">

                    <div v-if="invoice.payment?.status == 'paid'">
                        <div class="flex items-center justify-center">
                            <svg class="w-20 h-20 text-green-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <p class="font-bold">Pembayaran berhasil</p>
                    </div>

                    <div v-else-if="invoice.payment?.status == 'declined'">
                        <div class="flex items-center justify-center">
                            <svg class="w-20 h-20 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="font-bold">Pembayaran gagal</p>
                    </div>

                    <div v-else-if="invoice.payment?.status == 'timeout'">
                        <div class="flex items-center justify-center">
                            <svg class="w-20 h-20 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="font-bold">Pembayaran kadaluarsa / timeout</p>
                    </div>

                    <div v-else>
                        <div class="flex items-center justify-center">
                            <svg class="w-20 h-20 text-yellow-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <p>Cek notifikasi kamu atau buka aplikasi OVO untuk menyelesaikan pembayaran</p>
                    </div>
              </div>
              <div class="mb-2">
                <h3 class="text-gray-600">Invoice</h3>
                <p>{{ invoice.id }}</p>
              </div>
              <div class="mb-2">
                <h3 class="text-gray-600">Status Pembayaran:</h3>
                <span class="bg-green-200 px-2 py-1 inline-block rounded border border-green-300" v-if="invoice.payment?.status == 'paid'">PAID</span>
                <span class="bg-red-200 px-2 py-1 inline-block rounded border border-red-300" v-else-if="invoice.payment?.status == 'declined'">DECLINED</span>
                <span class="bg-red-200 px-2 py-1 inline-block rounded border border-red-300" v-else-if="invoice.payment?.status == 'timeout'">TIMEOUT</span>
                <span class="bg-yellow-200 px-2 py-1 inline-block rounded border border-yellow-300" v-else>PENDING</span>
              </div>

          </div>
      </div>
      <div v-if="invoice.payment === undefined || invoice.payment?.status == 'unpaid'">
          <button class="bg-indigo-600 w-full text-center text-white rounded p-3"
            @click="handleCheckStatus"
          >
            Cek Status Pembayaran
          </button>
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
    props: ['invoice'],
    setup(props){

        const state = reactive({
            product: props.invoice.product,
            response: props.invoice.response,
            timer: 60
        })

        const handleCheckStatus = () => {
            Inertia.get(route('invoice.check', props.invoice.id))
        }

        onMounted(() => {

            setTimeout(() => {
                if(props.invoice.payment === undefined || props.invoice.payment?.status == 'unpaid'){
                    Inertia.get(route('invoice.check', props.invoice.id))
                }
            }, 3000);
        })

        return {
            ...toRefs(state),
            handleCheckStatus
        }

    }
}
</script>

<style>

</style>
