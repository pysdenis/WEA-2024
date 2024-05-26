<script lang="ts">
	import { onMount } from 'svelte';
	import { postRegisterData } from '../api/postDataToDatabase';

	let email = '';
	let loginName = '';
	let loginPassword = '';
	let loginPasswordRepeat = '';

	let passwordMatch = true;
	let showPassword = false;

	async function register() {
		if (loginPassword !== loginPasswordRepeat) {
			passwordMatch = false;
			return;
		}

		const response = await postRegisterData({ email, loginName, loginPassword });
		console.log(response);
	}

	onMount(() => {
		// TODO: delete
		document.body.classList.add('bg-gray-100', 'p-4');
	});
</script>

<main>
	<h1 class="text-2xl font-bold mb-4">Register</h1>
	{#if !passwordMatch}
		<p class="text-red-500 mb-4">Passwords do not match</p>
	{/if}
	<form on:submit|preventDefault={register} class="max-w-md">
		<div class="mb-4">
			<label class="block mb-2" for="email">Email:</label>
			<input type="email" id="email" class="w-full px-4 py-2 border rounded" bind:value={email} />
		</div>
		<div class="mb-4">
			<label class="block mb-2" for="loginName">Username:</label>
			<input type="text" id="loginName" class="w-full px-4 py-2 border rounded" bind:value={loginName} />
		</div>
		<div class="mb-4">
			<label class="block mb-2" for="loginPassword">Password:</label>
			<div class="relative">
				{#if !showPassword}
					<input type="password" id="loginPassword" class="w-full px-4 py-2 border rounded" bind:value={loginPassword} />
				{:else}
					<input type="text" id="loginPassword" class="w-full px-4 py-2 border rounded" bind:value={loginPassword} />
				{/if}
				<button type="button" class="absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-500" on:click={() => showPassword = !showPassword}>
					{showPassword ? 'Hide' : 'Show'}
				</button>
			</div>
		</div>
		<div class="mb-4">
			<label class="block mb-2" for="loginPasswordRepeat">Repeat password:</label>
			<div class="relative">
				{#if !showPassword}
					<input type="password" id="loginPasswordRepeat" class="w-full px-4 py-2 border rounded" bind:value={loginPasswordRepeat} />
				{:else}
					<input type="text" id="loginPasswordRepeat" class="w-full px-4 py-2 border rounded" bind:value={loginPasswordRepeat} />
				{/if}
				<button type="button" class="absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-500" on:click={() => showPassword = !showPassword}>
					{showPassword ? 'Hide' : 'Show'}
				</button>
			</div>
		</div>
		<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Register</button>
	</form>
</main>
