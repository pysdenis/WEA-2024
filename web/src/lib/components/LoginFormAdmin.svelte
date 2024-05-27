<script lang="ts">
	import { postLoginData } from "$lib/api/postDataToDatabase";
	import StaticPicture from "./picture/StaticPicture.svelte";
	import logo from "$lib/assets/images/logo.png";
	import Icon from './Icon.svelte';
	import closed from '$lib/assets/icons/closed.svg?raw';
	import opened from '$lib/assets/icons/opened.svg?raw';
	import { onMount } from "svelte";

	let email = '';
	let loginPassword = '';
	let showPassword = false;
	let wrongCredentials = false;

	onMount(() => {
		const token = localStorage.getItem('token');
		if (token) {
			window.location.assign('/admin');
		}
	});

	async function login() {
		try {
			const response = await postLoginData({ email, loginPassword });
			if (response.token) {
				localStorage.setItem('token', JSON.stringify(response.token));
				window.location.assign('/admin');
			} else {
				throw new Error('No token in response');
			}
		} catch (error) {
			wrongCredentials = true;
		}
	}
</script>

<main class="flex justify-center h-[100dvh] w-[100dvw] bg-gray-200 items-center">
	<div class="flex flex-col items-center gap-3 mb-12">
		<div class="flex justify-between w-full items-center">
			<h1 class="text-xl m-0 p-0 uppercase">Přihlášení</h1>
			<StaticPicture image={logo} height={48} width={48} alt="Logo THE CAP" imgClass="h-12" />
		</div>
		<form on:submit|preventDefault={login} class="flex flex-col gap-4">
			<label class="flex flex-col">
				Email
				<input type="text" bind:value={email} class="p-2" />
			</label>
			<label class="flex flex-col">
				Heslo
				<div class="relative">
					{#if showPassword}
					<input type="text" bind:value={loginPassword} class="p-2" />
					{:else}
					<input type="password" bind:value={loginPassword} class="p-2" />
					{/if}
					<button type="button" class="absolute top-1/2 right-2 transform -translate-y-1/2" on:click={() => showPassword = !showPassword}>
						{#if showPassword}
							<Icon icon={opened} class="w-4 h-4 bg-white" />
						{:else}
							<Icon icon={closed} class="w-4 h-4 bg-white" />
						{/if}
					</button>
				</div>
			</label>
			<button type="submit" class="p-2 bg-black text-white text-xs hover:scale-105 transition-all duration-300">Přihlásit se</button>
		</form>
		{#if wrongCredentials}
			<p class="text-red-500 mt-4 text-3xs text-center">Špatné přihlašovací údaje</p>
		{/if}
	</div>
</main>
