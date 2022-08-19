<template>
  <div class="py-2 mb-5">
    <span class="mb-1 block">Enter your phone number</span>
    <input
      type="tel"
      class="w-full rounded-sm border-gray-300"
      placeholder="08xxxxxxxxx"
      v-model="phone"
    />
    <div
      class="bg-red-100 my-3 border-red-500 border rounded-sm text-red-600 p-3"
      v-if="error != ''"
    >
      {{ error.rd }}
    </div>
    <div class="mt-3 py-3 border-t">
      <h3 class="font-bold">Instructions</h3>
      <ul class="list-disc px-4 text-gray-700">
        <!-- <li>Buka aplikasi OVO kamu untuk melakukan pembayaran.</li>
                  <li>Pastikan segera melakukan pembayaran untuk menghindari pembatalan order secara otomatis</li> -->
        <!-- After click "Bayar", open your OVO application notification / Open your OVO app, if the confirmation page isn’t there please click the bell icon on the upper right to go to your OVO confirmation page to pay your bill in <merchant app name> -->

        <!-- <li>Enter your registered phone number in the OVO application with the 08xxx format.</li> -->
        <li>
          After click "Pay", open your OVO application notification / Open your
          OVO app, if the confirmation page isn’t there please click the bell
          icon on the upper right to go to your OVO confirmation page to pay
          your bill in WinPay
        </li>
        <li>
          Review your payment confirmation in the OVO app and please pay
          immediately (under 55s) to avoid transaction time out with click “Pay”
        </li>
        <li>You can choose OVO Cash or OVO Points.</li>
      </ul>
    </div>
  </div>
</template>
