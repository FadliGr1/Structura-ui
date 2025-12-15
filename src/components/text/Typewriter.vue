<template>
  <span ref="typedElement" class="typed-text-inner"></span>
</template>

<script setup>
import {ref, onMounted, onUnmounted, watch} from "vue";
import Typed from "typed.js";

const props = defineProps({
  settings: {
    type: Object,
    required: true,
    default: () => ({
      strings: [],
      typeSpeed: 50,
      backSpeed: 30,
      startDelay: 0,
      loop: true,
      cursorChar: "|",
    }),
  },
});

const typedElement = ref(null);
let typedInstance = null;

const initTyped = () => {
  // Find the target element.
  // Since Vue mounts on the wrapper, we look for the specific span inside the wrapper
  // OR we simply use the ref if we were rendering the whole thing.

  // Correction: Bricks PHP renders: <div wrapper> <span prefix> <span typed-target> <span suffix> </div>
  // If we mount Vue on <div wrapper>, Vue will wipe the innerHTML (prefix/suffix).
  // CRITICAL FIX: We should NOT mount Vue on the wrapper.
  // We should mount Vue on the <span typed-text> ONLY.

  if (!typedElement.value) return;

  // Cleanup
  if (typedInstance) typedInstance.destroy();

  // Init
  typedInstance = new Typed(typedElement.value, {
    strings: props.settings.strings,
    typeSpeed: props.settings.typeSpeed,
    backSpeed: props.settings.backSpeed,
    startDelay: props.settings.startDelay,
    loop: props.settings.loop,
    cursorChar: props.settings.cursorChar,
  });
};

onMounted(() => {
  initTyped();
});

onUnmounted(() => {
  if (typedInstance) typedInstance.destroy();
});

// Watch for changes (Builder Preview)
watch(
  () => props.settings,
  () => {
    initTyped();
  },
  {deep: true}
);
</script>

<style>
/* Global Typed Styles */
.typed-cursor {
  opacity: 1;
  animation: typedjsBlink 0.7s infinite;
}

@keyframes typedjsBlink {
  50% {
    opacity: 0;
  }
}
</style>
