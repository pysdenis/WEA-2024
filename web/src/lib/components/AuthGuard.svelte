<script lang="ts">
	import { isAuthenticated } from '$lib/scripts/auth';
	import { onMount } from 'svelte';

	onMount(() => {
		if (!isAuthenticated()) {
			window.location.assign('/admin/login');
		}
	});
</script>

<slot />
