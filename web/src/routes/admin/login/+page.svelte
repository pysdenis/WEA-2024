<script lang="ts">
	import LoginFormAdmin from '$lib/components/LoginFormAdmin.svelte';
	import RegisterFormAdmin from '$lib/components/RegisterFormAdmin.svelte';
	let showRegisterForm = false;

</script>

{#if showRegisterForm}
	<RegisterFormAdmin />
	<button class="absolute text-2xs right-3 top-2" on:click={() => showRegisterForm = false}>Přihlaste se</button>
	<a href="/" class="absolute text-2xs left-3 top-2">Zpět na web</a>
{:else}
	<LoginFormAdmin />
	<button class="absolute text-2xs right-3 top-2" on:click={() => showRegisterForm = true}>Zaregistrujte se</button>
	<a href="/" class="absolute text-2xs left-3 top-2">Zpět na web</a>
{/if}
