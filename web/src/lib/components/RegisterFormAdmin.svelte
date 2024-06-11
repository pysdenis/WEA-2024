<script lang="ts">
	import { postLoginData, postRegisterData } from '../api/postDataToDatabase';
	import { passwordLowercasePattern, passwordUppercasePattern, passwordDigit } from '../regex/password';
	import StaticPicture from "./picture/StaticPicture.svelte";
	import logo from "$lib/assets/images/logo.png";
	import Icon from './Icon.svelte';
	import closed from '$lib/assets/icons/closed.svg?raw';
	import opened from '$lib/assets/icons/opened.svg?raw';
	import Logger from './Logger.svelte';

	let showLogger = false;
	let loggerMsg: string | unknown;

	let email = '';
	let loginName = '';
	let loginPassword = '';
	let loginPasswordRepeat = '';

	let passwordMatch = true;
	let showPassword = false;
	let passwordLowercase = true;
	let passwordUppercase = true;
	let passwordNumber = true;
	let emailValid = true;

	async function register() {
		if (loginPassword !== loginPasswordRepeat) {
			passwordMatch = false;
			return;
		}

		if (!passwordLowercasePattern.test(loginPassword)) {
			passwordLowercase = false;
			return;
		}

		if (!passwordUppercasePattern.test(loginPassword)) {
			passwordUppercase = false;
			return;
		}

		if (!passwordDigit.test(loginPassword)) {
			passwordNumber = false;
			return;
		}

		if (!email.includes('@')) {
			emailValid = false;
			return;
		}

		try {
			const response = await postRegisterData({ email, loginName, loginPassword });
			if (response.token) {
				localStorage.setItem('token', JSON.stringify(response.token));
				window.location.assign('/admin');
			} else {
				showLogger = true;
				loggerMsg = JSON.stringify(response.message);
			}
		} catch (error) {
			showLogger = true;
			loggerMsg = error;
		}
	}
</script>

<main class="flex justify-center h-[100dvh] w-[100dvw] bg-gray-200 items-center">
	<div class="mb-12">
		<div class="flex justify-between w-full items-center">
			<h1 class="text-xl m-0 p-0 uppercase">Registrace</h1>
			<StaticPicture image={logo} height={48} width={48} alt="Logo THE CAP" imgClass="h-12" />
		</div>
		<form on:submit|preventDefault={register} class="flex flex-col md:grid grid-cols-2 gap-4">
			<label class="flex flex-col">
				Email
				<input type="text" bind:value={email} class="p-2" />
			</label>
			<label class="flex flex-col">
				Jméno
				<input type="text" bind:value={loginName} class="p-2" />
			</label>
			<label class="flex flex-col">
				<span>Heslo<span class="text-3xs">(Heslo musí obsahovat velké písmeno a číslici)</span></span>
				<div class="relative">
					{#if showPassword}
						<input type="text" bind:value={loginPassword} class="p-2" />
					{:else}
						<input type="password" bind:value={loginPassword} class="p-2" />
					{/if}
				</div>
			</label>
			<label class="flex flex-col">
				Heslo znovu
				<div class="relative">
					{#if showPassword}
						<input type="text" bind:value={loginPasswordRepeat} class="p-2" />
					{:else}
						<input type="password" bind:value={loginPasswordRepeat} class="p-2" />
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
			<button type="submit" class="p-2 bg-black col-span-2 text-white text-xs hover:scale-105 transition-all duration-300">Registrovat se</button>
		</form>
		{#if !passwordMatch}
			<p class="text-red-500 mb-4">Hesla se neshodují</p>
		{/if}
		{#if !passwordLowercase}
			<p class="text-red-500 mb-4">Heslo musí obsahovat malé písmeno</p>
		{/if}
		{#if !passwordUppercase}
			<p class="text-red-500 mb-4">Heslo musí obsahovat velké písmeno</p>
		{/if}
		{#if !passwordNumber}
			<p class="text-red-500 mb-4">Heslo musí obsahovat číslo</p>
		{/if}
		{#if !emailValid}
			<p class="text-red-500 mb-4">Email není validní</p>
		{/if}
	</div>
	{#if showLogger}
		<Logger message={loggerMsg} on:close={() => showLogger = false} />
	{/if}
</main>
