<script lang="ts">
	import '$lib/assets/css/main.css';
	import logo from '$lib/assets/images/logo.png';
	import { fetchCategories } from '$lib/api/fetchFromDatabase';
	import { onMount } from 'svelte';
	import type Category from '$lib/types/Category';

	let categories: Category[] = [];
	let showOthers = false;

	onMount(async () => {
		const response = await fetchCategories();
		categories = response as Category[];
	});
</script>

<header class="flex py-4 bg-primary">
	<div class="container flex items-center justify-between gap-12">
		<div class="flex h-14 w-14">
			<a href="/">
				<img src={logo} height={56} width={56} alt="Logo THE CAP" />
			</a>
		</div>
		<nav class="text-white h-full flex items-center gap-8 uppercase">
			{#each categories.slice(0, 5) as category}
				<span class="flex justify-end items-center">
					<a class="flex" href={`/kategorie/${category.urlSlug}`}>{category.name}</a>
				</span>
			{/each}
			{#if categories.length > 5}
				<span class="flex justify-end items-center relative">
					<button type="button" on:click={() => showOthers = !showOthers} class="flex uppercase cursor-pointer">Další kategorie</button>
					<div role="menu" tabindex="0" class="hidden absolute right-0 top-5 mt-2 py-2 w-48 bg-white rounded-md shadow-lg" class:!block={showOthers} on:mouseenter={() => {setTimeout(() => {showOthers = false}, 300)}} on:mouseleave={() => {showOthers = true}}>
						{#each categories.slice(5) as category}
							<a href={`/kategorie/${category.urlSlug}`} class="block px-4 py-2 text-sm text-gray-700 duration-300 hover:bg-gray-100">{category.name}</a>
						{/each}
					</div>
				</span>
			{/if}
		</nav>
	</div>
</header>
<main class="min-h-[88dvh]">
	<slot />
</main>
<footer class="flex items-center justify-center bg-secondary">
	<div class="container">
		<p class="text-text text-center">Denis Pyš | © 2024 | The Cap</p>
	</div>
</footer>
