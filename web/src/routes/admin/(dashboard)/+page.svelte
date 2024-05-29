<script lang="ts">
	import { adminStore } from '$lib/stores/adminStore';
</script>

{#if $adminStore}
	<h1>Přehled</h1>
	<p>Vítej {$adminStore.loginName}!</p>
	<!-- TODO: mby nějaké stats pokud zbyde čas -->
{/if}
