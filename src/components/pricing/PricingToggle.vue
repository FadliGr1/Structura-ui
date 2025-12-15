<template>
  <div class="pricing-reactive-component flex flex-col items-center">
    <div class="toggle-wrapper relative flex items-center bg-gray-100 rounded-full p-1 cursor-pointer select-none mb-6" @click="toggle">
      <div class="toggle-slider absolute left-1 top-1 bottom-1 w-[calc(50%-4px)] bg-white rounded-full shadow-sm transition-transform duration-300 toggle-active-bg" :class="{'translate-x-full': isYearly}"></div>

      <div class="relative z-10 px-4 py-1 text-xs font-bold transition-colors duration-300" :class="isYearly ? 'text-gray-500' : 'text-gray-900'">Monthly</div>
      <div class="relative z-10 px-4 py-1 text-xs font-bold transition-colors duration-300" :class="isYearly ? 'text-gray-900' : 'text-gray-500'">Yearly</div>
    </div>

    <div class="price-display text-center relative">
      <div class="flex items-baseline justify-center">
        <span class="text-2xl font-semibold mr-1 text-gray-500">$</span>

        <span class="pricing-amount text-5xl font-extrabold text-gray-900 tracking-tight">
          {{ currentPrice }}
        </span>

        <span class="text-gray-400 ml-2 font-medium">
          {{ currentPeriod }}
        </span>
      </div>

      <transition name="fade">
        <div v-if="isYearly && settings.yearly.discount" class="absolute -top-3 -right-16 bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full transform rotate-12">
          {{ settings.yearly.discount }}
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import {ref, computed} from "vue";

const props = defineProps({
  settings: {
    type: Object,
    required: true,
  },
});

const isYearly = ref(props.settings.isYearlyDefault || false);

const toggle = () => {
  isYearly.value = !isYearly.value;
};

const currentPrice = computed(() => {
  return isYearly.value ? props.settings.yearly.price : props.settings.monthly.price;
});

const currentPeriod = computed(() => {
  return isYearly.value ? props.settings.yearly.period : props.settings.monthly.period;
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(5px) rotate(12deg);
}
</style>
