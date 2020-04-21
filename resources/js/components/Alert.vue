<template>
	<div id="myToast" class="alert" :class="additionalClass" role="alert">
		<strong>{{title ? title : 'Success!'}}</strong>&ensp;{{text}}
		<button type="button" class="close" @click="removeAlert(type)" aria-label="Close">
    		<span aria-hidden="true">&times;</span>
  		</button>
	</div>
</template>
<script type="text/javascript" charset="utf-8">
	export default {
		name: 'alert',
		props: ['type', 'title', 'text'],
		computed: {
		additionalClass() {
				if (!this.type) return 'd-none';
				return 'alert-' + this.type;
			}
		},
		methods:{
			removeAlert: function(tp){
				var toast = document.getElementById('myToast');
				toast.className = toast.className.replace('alert-' + tp, "d-none");
			}
		}
	}
</script>