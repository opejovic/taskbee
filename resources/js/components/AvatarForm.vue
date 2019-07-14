<template>
	<div>
		<div class="display-4 d-flex align-items-center">
			<img
				class="mr-2"
				:src="avatar"
				width="60px"
				height="60px"
				alt="avatar"
				style="border-radius: 50%;"
				@click="$refs.fileInput.click()"
			/>
			<div>{{ profileuser.full_name }}</div>
		</div>

		<form method="post" enctype="multipart/form-data" v-if="canUpload">
			<input 
				type="file"
				style="display: none;"
				name="avatar" 
				@change="onChange" 
				accept="image/*" 
				ref="fileInput" />
		</form>
	</div>
</template>

<script>
	export default {
		props: ["profileuser"],

		data() {
			return {
				avatar: this.profileuser.avatar_path
			};
		},

		computed: {
			canUpload() {
				return this.profileuser.id == auth.id;
			}
		},

		methods: {
			onChange(event) {
				if (! event.target.files.length) return;

				let avatar = event.target.files[0];
				let data = new FileReader();
				data.readAsDataURL(avatar);

				data.onload = event => {
					this.avatar = event.target.result;
				};

				this.persist(avatar);
			},

			persist(avatar) {
				let data = new FormData();

				data.append("avatar", avatar);

				axios
					.post(`/profiles/${this.profileuser.id}/avatar`, data)
					.then(() => this.$toasted.show("Avatar saved."));
			}
		}
	};
</script>