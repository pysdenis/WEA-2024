<script lang="ts">
	import { onMount } from 'svelte';
	import { fetchArticles } from '$lib/api/fetchFromDatabase';
	import type Article from '$lib/types/Article';

	let articles: Article[] = [];
	let date = new Date();

	onMount(async () => {
		const response = await fetchArticles();
		articles = response as Article[];
	});
</script>

<svelte:head>
	<title>THE CAP</title>
</svelte:head>

<h1>Articles</h1>

{#each articles as article}
	{#if new Date(article.publishedAt) <= date}
		<article>
			<h2>{article.title}</h2>
			<p>{article.content}</p>
		</article>
	{/if}
{/each}
