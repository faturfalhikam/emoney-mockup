<template>
  <GuestLayout>
      <h1 class="text-lg font-bold">{{ product.name }}</h1>
      <h2>Rp: {{ product.price }}</h2>
      <hr class="my-2">
      <div class="py-2 mb-5">
          <span class="mb-1 block">Masukkan Nomor Telepon</span>
          <input type="tel" class="w-full rounded-sm border-gray-300" placeholder="08xxxxxxxxx" v-model="phone" />
          <div class="bg-red-100 my-3 border-red-500 border rounded-sm text-red-600 p-3" v-if="error != ''">
              {{ error.rd }}
          </div>
          <div class="mt-3 py-3 border-t">
              <h3 class="font-bold">Instruksi</h3>
              <ul class="list-disc px-4">
                  <li>Buka aplikasi OVO kamu untuk melakukan pembayaran.</li>
                  <li>Pastikan segera melakukan pembayaran untuk menghindari pembatalan order secara otomatis</li>
              </ul>
          </div>
      </div>
      <div>
          <Link class="bg-indigo-600 block text-center text-white rounded p-3"
            @click="handleClickPayWithOVO"
          >
            Bayar dengan OVO
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
