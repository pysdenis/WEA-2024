<script lang="ts">
	import '$lib/assets/css/main.css';
	import logo from '$lib/assets/images/logo.png';
	import { fetchCategories } from '$lib/api/fetchFromDatabase';
	import { onMount } from 'svelte';
	import type Category from '$lib/types/Category';
	import StaticPicture from '$lib/components/picture/StaticPicture.svelte';

	let categories: Category[] = [];

	onMount(async () => {
		const response = await fetchCategories();
		categories = response as Category[];
	});
</script>

<header class="flex items-center gap-3 justify-center py-3 bg-primary">
	<a href="/">
		<StaticPicture image={logo} height={44} width={44} alt="Logo THE CAP" class="h-11" />
	</a>
	<nav class="text-white">
		{#each categories as category}
			{#if category.inMenu}
				<a href="/{category.id}" class="px-3 py-2 rounded-md">{category.name}</a>
			{/if}
		{/each}
	</nav>
</header>
<main class="container">
	<slot />
</main>
<footer class="flex items-center justify-center bg-secondary">
	<p>footer</p>
</footer>
