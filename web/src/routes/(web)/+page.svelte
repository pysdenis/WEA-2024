<script lang="ts">
	import { onMount } from 'svelte';
	import { fetchArticles } from '$lib/api/fetchFromDatabase';
	import type Article from '$lib/types/Article';
	import ArticleCard from '../../lib/components/ArticleCard.svelte';

	let articles: Article[] = [];

	onMount(async () => {
		const response = await fetchArticles();
		articles = response as Article[];
	});
</script>

<svelte:head>
	<title>THE CAP</title>
</svelte:head>
<section class="w-full flex flex-col items-center justify-center h-56 bg-gradient-dark text-white">
	<h1 class="text-5xl">For the meme culture</h1>
</section>
<section class="container">
	<h2 class="text-center">Články</h2>
	<div class="grid grid-cols-1 mt-12 md:grid-cols-2 lg:grid-cols-3 gap-8">
		{#if articles.length === 0}
			<p class="text-center">Žádné články</p>
		{:else}
			{#each articles as article}
				<ArticleCard {article} />
			{/each}
		{/if}
	</div>
</section>
