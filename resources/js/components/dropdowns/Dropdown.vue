<template>
	<on-click-outside :do="handleClickOutside">
		<div class="relative w-64 mx-auto">
			<button
				@click="open = !open"
				type="button"
				class="w-full block bg-blue text-white font-semibold rounded px-4 py-2 focus:outline-0"
			>
				Click to open
			</button>
			<div
				v-show="open"
				class="mt-px text-sm text-center text-grey-darker leading-normal rounded bg-white border p-4"
			>
				<p>Click outside to close.</p>
			</div>
		</div>
	</on-click-outside>
</template>

<script>
	import OnClickOutside from "./OnClickOutside.vue";

	export default {
		name: "DropdownDemo",
		components: {
			OnClickOutside
		},
		data() {
			return {
				open: false
			};
		},
		methods: {
			handleClickOutside() {
				if (this.open) {
					this.open = false;
				}
			}
		}
	};
</script>