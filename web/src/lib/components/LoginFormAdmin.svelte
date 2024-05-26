<script>
	import { postLoginData } from "$lib/api/postDataToDatabase";

	let email = '';
	let loginPassword = '';
	let showPassword = false;

	async function login() {
		try {
			const response = await postLoginData({ email, loginPassword });
			console.log(response);
		} catch (error) {
			console.error(error);
		}
	}
</script>

<main class="max-w-400px mx-auto p-8">
	<h1 class="text-center">Login</h1>
	<form on:submit|preventDefault={login} class="flex flex-col gap-4">
		<label class="flex flex-col">
			Email:
			<input type="text" bind:value={email} class="p-2" />
		</label>
		<label class="flex flex-col">
			Password:
			<div class="relative">
				{#if showPassword}
					<input type="text" bind:value={loginPassword} class="p-2" />
				{:else}
					<input type="password" bind:value={loginPassword} class="p-2" />
				{/if}
				<button type="button" class="absolute top-1/2 right-2 transform -translate-y-1/2" on:click={() => showPassword = !showPassword}>
					{showPassword ? "Hide" : "Show"}
				</button>
			</div>
		</label>
		<button type="submit" class="p-2">Login</button>
	</form>
</main>

<style>
	/* Remove the existing styles */

	/* Add Tailwind CSS classes */
</style>
