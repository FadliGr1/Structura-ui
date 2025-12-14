<script setup>
import {ref, computed} from "vue";

/**
 * Props Definition
 */
const props = defineProps({
  settings: {type: Object, default: () => ({})},
});

/**
 * State
 */
const activeIndex = ref(null);

/**
 * Computed Styles
 */
const markerBaseStyle = computed(() => ({
  backgroundColor: props.settings.markerColor || "#3b82f6",
  color: props.settings.iconColor || "#ffffff",
}));

/**
 * Interaction Logic
 */
const handleInteraction = (index, type) => {
  // Mobile friendly: Tap to toggle
  if (type === "click") {
    activeIndex.value = activeIndex.value === index ? null : index;
  }
  // Desktop hover
  if (type === "enter") activeIndex.value = index;
  if (type === "leave") activeIndex.value = null;
};

const getLinkUrl = (linkObj) => {
  if (!linkObj) return null;
  if (typeof linkObj === "string") return linkObj;
  if (linkObj.url) return linkObj.url;
  return null;
};
</script>

<template>
  <div class="s-hotspot-container relative w-full overflow-visible select-none rounded-xl">
    <img :src="settings.image" class="w-full h-auto block rounded-xl shadow-lg object-cover" alt="Interactive Hotspot Image" loading="lazy" />

    <div class="absolute inset-0 bg-black/5 rounded-xl pointer-events-none"></div>

    <div
      v-for="(point, index) in settings.points"
      :key="index"
      class="s-hotspot-marker absolute z-10 flex items-center justify-center cursor-pointer"
      :style="{left: `${point.x}%`, top: `${point.y}%`}"
      @click="handleInteraction(index, 'click')"
      @mouseenter="handleInteraction(index, 'enter')"
      @mouseleave="handleInteraction(index, 'leave')"
    >
      <span v-if="settings.pulse" class="absolute w-full h-full rounded-full opacity-75 animate-ping" :style="{backgroundColor: settings.markerColor || '#3b82f6'}"></span>

      <div class="relative w-8 h-8 md:w-10 md:h-10 rounded-full shadow-lg border-2 border-white flex items-center justify-center transition-transform transform hover:scale-110 active:scale-95" :style="markerBaseStyle">
        <i :class="point.icon" class="text-sm md:text-base"></i>
      </div>

      <transition name="fade-slide">
        <div
          v-if="activeIndex === index"
          class="s-tooltip-card absolute z-20 w-64 bg-white p-4 rounded-lg shadow-2xl text-left"
          :class="{
            'bottom-full mb-4': point.y > 50 /* Auto position top */,
            'top-full mt-4': point.y <= 50 /* Auto position bottom */,
            '-translate-x-1/2': true /* Center horizontally */,
          }"
        >
          <div class="flex justify-between items-start mb-1">
            <h4 class="text-sm font-bold text-gray-900">{{ point.title }}</h4>
            <span v-if="point.price" class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
              {{ point.price }}
            </span>
          </div>

          <p class="text-xs text-gray-500 leading-relaxed mb-3">{{ point.desc }}</p>

          <a v-if="point.link" :href="getLinkUrl(point.link)" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
            View Details
            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
          </a>

          <div class="absolute w-3 h-3 bg-white transform rotate-45 left-1/2 -ml-1.5" :class="point.y > 50 ? '-bottom-1.5' : '-top-1.5'"></div>
        </div>
      </transition>
    </div>
  </div>
</template>

<style lang="scss" scoped>
/* Scoped to avoid conflicts */
.s-hotspot-container {
  /* Ensuring tooltips are visible even if they extend outside */
  /* If image is full width, sometimes overflow-hidden cuts tooltips. visible is safer. */
  overflow: visible;
}

/* Tooltip Animation */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(10px); /* Slide up effect */
}
</style>
