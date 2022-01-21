<template>
  <GuestLayout>
    <h2 class="border-b pb-3 font-bold">Products</h2>
    <ul class="grid grid-cols-4 gap-3 my-2">
        <li v-for="(prod, key) in products" :key="key" class="hover:bg-green-50 border-transparent border hover:border hover:border-green-500">
            <Link :href="route('checkout.check', key)" class="flex flex-col justify-center items-center p-2">
                <img class="rounded-sm" :src="'https://placem.at/things?w=100&h=100&random='+key" alt="" />
                <p class="text-sm font-medium text-gray-900 my-1 text-center">{{ prod.name }}</p>
            </Link>
        </li>
    </ul>

    <h2 class="font-bold border-b py-3 ">Transaksi Terakhir</h2>
    <ul class="divide-y">
        <li v-for="invoice in invoices" :key="invoice.id">
            <Link :href="route('invoice.check', invoice.id)" class="flex justify-between p-1 hover:bg-green-50 border border-transparent hover:border-green-500">
                <div class="">
                    <div class="block text-md">{{ invoice.product.name }}</div>
                    <div class="block text-xs text-gray-500">{{ invoice.phone }}</div>
                </div>
                <div>
                    <div class="block text-xs ">Rp. {{ invoice.product.price }}</div>
                </div>
            </Link>
        </li>
    </ul>
  </GuestLayout>
</template>

<script>
import GuestLayout from '@/Layouts/Guest'
import {Link} from '@inertiajs/inertia-vue3'
export default {
    components:{
        GuestLayout,
        Link
    },
    props: ['products', 'invoices'],
    setup(props){

    }
}
</script>

<style>

</style>
