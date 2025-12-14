<template>
  <div class="relative w-full overflow-hidden rounded-lg shadow-md group select-none">
    <img :src="settings.imageUrl" alt="Hotspot Map" class="w-full h-auto object-cover block pointer-events-none" loading="lazy" />

    <div class="absolute inset-0 bg-black/5 pointer-events-none"></div>

    <div
      v-for="(hotspot, index) in settings.hotspots"
      :key="index"
      class="hotspot-marker absolute cursor-pointer z-10 flex items-center justify-center"
      :style="{left: hotspot.x + '%', top: hotspot.y + '%'}"
      @mouseenter="handleInteraction(index, 'enter')"
      @mouseleave="handleInteraction(index, 'leave')"
      @click="handleInteraction(index, 'click')"
    >
      <div
        class="relative w-8 h-8 rounded-full shadow-lg transition-transform duration-300 hover:scale-110 flex items-center justify-center border-2 border-white overflow-hidden"
        :class="activeHotspot === index ? 'scale-110 ring-4 ring-white/30' : ''"
        :style="{backgroundColor: settings.markerColor || '#3b82f6'}"
      >
        <img v-if="hotspot.type === 'image' && hotspot.image" :src="hotspot.image" alt="icon" class="w-full h-full object-cover" />

        <i v-else :class="hotspot.icon" class="text-sm" :style="{color: settings.iconColor || '#ffffff'}"></i>

        <span v-if="activeHotspot !== index" class="absolute -inset-1 rounded-full opacity-75 animate-ping z-[-1]" :style="{backgroundColor: settings.markerColor || '#3b82f6'}"></span>
      </div>

      <transition name="fade-slide">
        <div v-if="activeHotspot === index" class="hotspot-popup absolute bottom-full left-1/2 mb-4 w-64 -translate-x-1/2 rounded-lg bg-white p-4 shadow-xl text-left z-20" @click.stop>
          <div class="absolute -bottom-1.5 left-1/2 h-3 w-3 -translate-x-1/2 rotate-45 bg-white shadow-sm"></div>

          <div class="relative z-10">
            <div class="flex justify-between items-start mb-2">
              <h4 class="text-sm font-bold text-gray-900 leading-tight">
                {{ hotspot.title }}
              </h4>
              <span v-if="hotspot.price" class="ml-2 px-2 py-0.5 text-[10px] font-bold text-green-700 bg-green-100 rounded-full whitespace-nowrap">
                {{ hotspot.price }}
              </span>
            </div>

            <p class="text-xs text-gray-500 leading-relaxed mb-3">
              {{ hotspot.description }}
            </p>

            <a v-if="hotspot.link && hotspot.link.url" :href="hotspot.link.url" :target="hotspot.link.target || '_self'" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
              View Details
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";

/**
 * Props Definition
 */
const props = defineProps({
  settings: {
    type: Object,
    required: true,
    default: () => ({
      imageUrl: "",
      hotspots: [],
      markerColor: "#3b82f6",
      iconColor: "#ffffff",
    }),
  },
});

/**
 * Active State
 */
const activeHotspot = ref(null);

/**
 * Interaction Handler
 * Handles both Hover (Desktop) and Click (Mobile)
 */
const handleInteraction = (index, type) => {
  if (type === "click") {
    // Toggle on click
    activeHotspot.value = activeHotspot.value === index ? null : index;
  } else if (type === "enter") {
    activeHotspot.value = index;
  } else if (type === "leave") {
    activeHotspot.value = null;
  }
};
</script>

<style scoped lang="scss">
/* * Center Marker Logic
 * Translates the marker so its center aligns with the coordinate.
 */
.hotspot-marker {
  transform: translate(-50%, -50%);
}

/* * Tooltip Transition 
 */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translate(-50%, 10px);
}
</style>
