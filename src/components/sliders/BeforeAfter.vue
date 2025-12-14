<script setup>
import {ref, onMounted} from "vue";

/**
 * Props Definition
 */
const props = defineProps({
  settings: {type: Object, default: () => ({})},
});

/**
 * References
 */
const containerRef = ref(null);
const sliderPosition = ref(50); // Percentage (0-100)
const isDragging = ref(false);

/**
 * Handle Mouse/Touch Move
 * Calculates the cursor position relative to the container.
 * * @param {Event} event - Mouse or Touch event.
 */
const handleMove = (event) => {
  if (!isDragging.value || !containerRef.value) return;

  const rect = containerRef.value.getBoundingClientRect();
  const isHorizontal = props.settings.orientation !== "vertical";

  // Get coordinate (X for horizontal, Y for vertical)
  const clientX = event.touches ? event.touches[0].clientX : event.clientX;
  const clientY = event.touches ? event.touches[0].clientY : event.clientY;

  let pos = 0;

  if (isHorizontal) {
    pos = ((clientX - rect.left) / rect.width) * 100;
  } else {
    pos = ((clientY - rect.top) / rect.height) * 100;
  }

  // Clamp values between 0 and 100
  sliderPosition.value = Math.max(0, Math.min(100, pos));
};

/**
 * Start Dragging
 */
const startDrag = () => {
  isDragging.value = true;
};

/**
 * Stop Dragging
 */
const stopDrag = () => {
  isDragging.value = false;
};

/**
 * Lifecycle Hook
 * Initialize starting position.
 */
onMounted(() => {
  if (props.settings.startPos) {
    sliderPosition.value = props.settings.startPos;
  }
});
</script>

<template>
  <div
    ref="containerRef"
    class="relative w-full overflow-hidden select-none group cursor-ew-resize"
    :class="settings.orientation === 'vertical' ? 'h-[500px] cursor-ns-resize' : 'aspect-video'"
    @mousemove="handleMove"
    @touchmove="handleMove"
    @mouseup="stopDrag"
    @mouseleave="stopDrag"
    @touchend="stopDrag"
  >
    <img :src="settings.afterImage" class="absolute inset-0 w-full h-full object-cover pointer-events-none" alt="After View" />

    <span class="absolute top-4 right-4 bg-black/50 text-white text-xs px-2 py-1 rounded backdrop-blur-sm z-10">
      {{ settings.afterLabel }}
    </span>

    <div
      class="absolute inset-0 h-full overflow-hidden border-r-2 border-white/50"
      :style="{
        width: settings.orientation === 'vertical' ? '100%' : `${sliderPosition}%`,
        height: settings.orientation === 'vertical' ? `${sliderPosition}%` : '100%',
        borderRightWidth: settings.orientation === 'vertical' ? '0px' : '2px',
        borderBottomWidth: settings.orientation === 'vertical' ? '2px' : '0px',
      }"
    >
      <img
        :src="settings.beforeImage"
        class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none max-w-none"
        :style="{
          width: settings.orientation === 'vertical' ? '100%' : `${100 * (100 / sliderPosition)}%`,
          height: settings.orientation === 'vertical' ? `${100 * (100 / sliderPosition)}%` : '100%',
          // Fix: Simply setting width 100vw/container width is safer for object-fit
          width: containerRef ? `${containerRef.offsetWidth}px` : '100%',
          height: containerRef ? `${containerRef.offsetHeight}px` : '100%',
        }"
        alt="Before View"
      />

      <span class="absolute top-4 left-4 bg-black/50 text-white text-xs px-2 py-1 rounded backdrop-blur-sm">
        {{ settings.beforeLabel }}
      </span>
    </div>

    <div
      class="absolute z-20 flex items-center justify-center w-8 h-8 -ml-4 -mt-4 bg-white rounded-full shadow-lg cursor-pointer transform hover:scale-110 transition-transform text-gray-600"
      :style="{
        left: settings.orientation === 'vertical' ? '50%' : `${sliderPosition}%`,
        top: settings.orientation === 'vertical' ? `${sliderPosition}%` : '50%',
      }"
      @mousedown="startDrag"
      @touchstart="startDrag"
    >
      <svg v-if="settings.orientation === 'vertical'" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" transform="rotate(90 12 12)" />
      </svg>
    </div>
  </div>
</template>
