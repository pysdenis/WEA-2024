<script lang="ts">
	import { adminStore } from '$lib/stores/adminStore';
</script>

{#if $adminStore}
	<h1>Dashboard</h1>
	<p>Welcome, {$adminStore.loginName}!</p>
{/if}
