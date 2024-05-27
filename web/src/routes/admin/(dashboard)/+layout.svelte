<script lang="ts">
	import '$lib/assets/css/main.css';
	import AuthGuard from '$lib/components/AuthGuard.svelte';
	import { onMount } from 'svelte';
	import { loadUserFromLocalStorage } from '$lib/stores/adminStore';
	import StaticPicture from "$lib/components/picture/StaticPicture.svelte";
	import logo from "$lib/assets/images/logo.png";

	onMount(() => {
		loadUserFromLocalStorage();
	});

	function logout(): void {
		localStorage.removeItem('token');
		window.location.assign('/admin/login');
	}
</script>

<svelte:head>
	<title>Administrace | THE CAP</title>
</svelte:head>

<AuthGuard>
	<main class="flex w-[100dvw] h-[100dvh]">
		<div class="w-1/6 bg-slate-800 p-3 flex flex-col justify-between h-full">
			<div>
				<div class="flex justify-center md:justify-between items-center">
					<a href="/admin">
						<StaticPicture image={logo} height={40} width={40} alt="Logo THE CAP" imgClass="h-10" />
					</a>
					<span class="text-sm font-normal hidden md:block m-0 p-0 text-white uppercase">ADMIN <span class="hidden xl:inline-block">| the cap</span></span>
				</div>
				<nav class="flex flex-col gap-2 mt-7">
					<a href="/admin" class="p-2 hover:bg-gray-200 upp bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">Přehled</a>
					<a href="/admin" class="p-2 hover:bg-gray-200 upp bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">Kategorie</a>
					<a href="/admin" class="p-2 hover:bg-gray-200 upp bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">Články</a>
					<a href="/admin" class="p-2 hover:bg-gray-200 upp bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">Autoři</a>
				</nav>
			</div>
			<div class="flex justify-center md:justify-between items-center mt-6">
				<button class="p-2 hover:bg-red-900 uppercase duration-300 transition-all text-2xs text-white rounded-md" on:click={logout}>Odhlásit</button>
				<a class="p-2 hover:bg-red-900 uppercase duration-300 transition-all text-2xs text-white rounded-md" href="/">Web</a>
			</div>
		</div>
		<div class="w-5/6 py-2 px-2 md:px-8">
			<slot />
		</div>
	</main>
</AuthGuard>
