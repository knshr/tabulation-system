<script setup>
/**
 * ConfirmModal - Global confirm/alert modal using daisyUI dialog
 *
 * Mount this once in app.js (already wired). It reads from the shared
 * useConfirm() state and opens/closes automatically.
 *
 * Supports:
 *   - Confirm mode: title, message, confirm + cancel buttons
 *   - Alert mode: title, message, single OK button
 *   - Variants: default (primary), danger (red), warning (yellow), info (blue)
 *   - Close on ESC key, close on backdrop click
 *   - Responsive: bottom on mobile, middle on desktop
 *
 * Designer: Customize the modal appearance using daisyUI and Tailwind classes below.
 */
import { ref, watch } from 'vue';
import { useConfirm } from '@/Composables/useConfirm';

const { state, handleConfirm, handleCancel, VARIANT_CLASSES } = useConfirm();

const dialogRef = ref(null);

watch(
    () => state.open,
    (isOpen) => {
        if (isOpen) {
            dialogRef.value?.showModal();
        } else {
            dialogRef.value?.close();
        }
    },
);

function onDialogClose() {
    if (state.open) {
        handleCancel();
    }
}
</script>

<template>
    <dialog
        ref="dialogRef"
        class="modal modal-bottom sm:modal-middle"
        @close="onDialogClose"
    >
        <div class="modal-box">
            <h3 class="text-lg font-bold">{{ state.title }}</h3>
            <p class="py-4">{{ state.message }}</p>
            <div class="modal-action">
                <button
                    v-if="state.mode === 'confirm'"
                    class="btn"
                    @click="handleCancel"
                >
                    {{ state.cancelText }}
                </button>
                <button
                    :class="VARIANT_CLASSES[state.variant] || VARIANT_CLASSES.default"
                    @click="handleConfirm"
                >
                    {{ state.confirmText }}
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</template>
