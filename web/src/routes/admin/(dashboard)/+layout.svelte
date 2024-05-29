<script lang="ts">
	import '$lib/assets/css/main.css';
	import AuthGuard from '$lib/components/AuthGuard.svelte';
	import { onMount } from 'svelte';
	import { loadUserFromLocalStorage } from '$lib/stores/adminStore';
	import StaticPicture from "$lib/components/picture/StaticPicture.svelte";
	import logo from "$lib/assets/images/logo.png";
	import dashboard from "$lib/assets/icons/dashboard.svg?raw";
	import categories from "$lib/assets/icons/categories.svg?raw";
	import articles from "$lib/assets/icons/articles.svg?raw";
	import authors from "$lib/assets/icons/authors.svg?raw";
	import logoutIcon from "$lib/assets/icons/logout.svg?raw";
	import website from "$lib/assets/icons/website.svg?raw";
	import Icon from '../../../lib/components/Icon.svelte';

	let currentTime = new Date();

	const updateTime = () => {
		currentTime = new Date();
	};

	let interval: number | undefined;

	onMount(() => {
		loadUserFromLocalStorage();

		interval = setInterval(updateTime, 1000);

		return () => {
			clearInterval(interval);
		};
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
				<div class="flex justify-center md:justify-start gap-3 items-center">
					<a href="/admin">
						<StaticPicture image={logo} height={40} width={40} alt="Logo THE CAP" imgClass="h-10" />
					</a>
					<span class="text-sm font-normal hidden md:block m-0 p-0 text-white uppercase">ADMIN <span class="hidden xl:inline-block">| the cap</span></span>
				</div>
				<nav class="flex flex-col gap-2 mt-7">
					<a href="/admin" class="p-2 items-center hover:bg-gray-200 lg:justify-start justify-center flex bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">
						<Icon icon={dashboard} class="w-5 h-5" />
						<span class="hidden lg:inline-block ml-3">Přehled</span>
					</a>
					<a href="/admin/categories" class="items-center p-2 hover:bg-gray-200 lg:justify-start justify-center flex bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">
						<Icon icon={categories} class="w-5 h-5" />
						<span class="hidden lg:inline-block ml-3">Kategorie</span>
					</a>
					<a href="/admin/articles" class="items-center p-2 hover:bg-gray-200 lg:justify-start justify-center flex bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">
						<Icon icon={articles} class="w-5 h-5" />
						<span class="hidden lg:inline-block ml-3">Články</span>
					</a>
					<a href="/admin/authors" class="items-center p-2 hover:bg-gray-200 lg:justify-start justify-center flex bg-gray-700 transition-all duration-300 text-white hover:text-text rounded-md text-center">
						<Icon icon={authors} class="w-5 h-5" />
						<span class="hidden lg:inline-block ml-3">Autoři</span>
					</a>
				</nav>
			</div>
			<div class="flex md:flex-row flex-col-reverse gap-2 md:justify-between items-center mt-6">
				<button class="p-2 hover:bg-red-900 uppercase duration-300 transition-all text-2xs text-white rounded-md" on:click={logout}>
					<Icon icon={logoutIcon} class="w-5 h-5 text-white" />
				</button>
				<a class="p-2 hover:bg-blue-900 uppercase duration-300 transition-all text-2xs text-white rounded-md" target="_blank" href="/">
					<Icon icon={website} class="w-5 h-5 text-white" />
				</a>
			</div>
		</div>
		<div class="w-5/6 bg-gray-200 py-2 px-2 flex flex-col pb-5 relative md:px-8">
			<span class="absolute text-3xs bottom-1 left-2">{currentTime.toLocaleString()}</span>
			<slot />
		</div>
	</main>
</AuthGuard>
