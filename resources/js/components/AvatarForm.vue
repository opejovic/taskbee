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
			<div class="form-group">
				<input
					type="file"
					class="form-control"
					:class="
						errorsHave('avatar')
							? ' is-invalid'
							: ''
					"
					style="display: none;"
					name="avatar"
					@change="onChange"
					accept="image/*"
					ref="fileInput"
				/>

				<span class="invalid-feedback" role="alert" v-if="errorsHave('avatar')">
					<strong v-text="get('avatar')"></strong>
				</span>
			</div>
		</form>
	</div>
</template>

<script>
	export default {
		props: ["profileuser"],

		data() {
			return {
				avatar: this.profileuser.avatar_path,
				errors: {}
			};
		},

		computed: {
			canUpload() {
				return this.profileuser.id == auth.id;
			}
		},

		methods: {
			onChange(event) {
				if (!event.target.files.length) return;

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
					.then(response => {
						this.$toasted.show("Avatar saved.");
						this.errors = {};
					})
					.catch(errors => {
						this.errors = errors.response.data.errors;
					});
			},

			errorsHave(field) {
				return this.errors.hasOwnProperty(field);
			},

			get(field) {
				if (this.errors[field]) {
					return this.errors[field][0];
				}
			}
		}
	};
</script>