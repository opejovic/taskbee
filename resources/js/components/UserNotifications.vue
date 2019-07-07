<template>
	<li class="nav-item dropdown" v-if="notifications.length">
		<a
			id="navbarDropdown"
			class="nav-link dropdown-toggle"
			href="#"
			role="button"
			data-toggle="dropdown"
			aria-haspopup="true"
			aria-expanded="false"
			v-pre
		>
			Notifications
			<span class="caret"></span>
		</a>

		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
			<a 
				class="dropdown-item" 
				href="#" 
				v-for="notification in notifications" 
				:key="notification.id"
				v-text="notificationText(notification)"
				@click="markAsRead(notification)"
			>
			
			</a>
		</div>
	</li>
</template>

<script>
	import moment from "moment";

	export default {
		props: ["user"],

		data() {
			return {
				notifications: false
			};
		},

		methods: {
			notificationText(notification) {
				var name = this.notificationOwner(notification);

				return this.formattedMessage(name, notification.data.message, notification.updated_at);
			},

			markAsRead(notification) {
				axios.delete(`/profiles/${this.user.id}/notifications/${notification.id}`)
				.then(this.notifications.splice(notification, 1));
			},

			formattedDate(date) {
                return moment(date).fromNow();
			},

			formattedMessage(name, message, date) {
				return name + ' ' + message + ' ' + this.formattedDate(date);
			},

			notificationOwner(notification) {
				return notification.data.member == this.user.full_name ? 'You' : notification.data.member;
			}
		},

		created() {
			axios
				.get(`/profiles/${this.user.id}/notifications`)
				.then(response => (this.notifications = response.data));
		}
	};
</script>

<style>
</style>


