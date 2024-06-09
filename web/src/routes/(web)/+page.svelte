<script lang="ts">
	import { onMount } from 'svelte';
	import { fetchArticles } from '$lib/api/fetchFromDatabase';
	import type Article from '$lib/types/Article';
	import ArticleCard from '../../lib/components/ArticleCard.svelte';

	let articles: Article[] = [];

	onMount(async () => {
		const response = await fetchArticles() as Article[];
		articles = response.filter(article => new Date(article.publishedAt) <= new Date()).reverse().splice(0, 6);
	});
</script>

<svelte:head>
	<title>THE CAP</title>
	<meta name="description" content="THE CAP je web plný zábavných memů a vtipů. Připojte se k nám a užijte si nejnovější trendy ze světa meme kultury.">
	<meta name="keywords" content="memy, vtipy, zábava, humor, internetová kultura, trendy, sociální média">
</svelte:head>

<div class="w-full flex flex-col items-center mb-8 lg:mb-16 justify-center h-56 bg-gradient-dark text-white">
	<h1 class="sm:text-5xl text-2xl">For the meme culture</h1>
</div>
<section class="container">
	<h2 class="text-center">Nejnovější Články</h2>
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
