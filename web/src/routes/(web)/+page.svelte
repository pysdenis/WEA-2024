<script lang="ts">
	import { onMount } from 'svelte';
	import { fetchArticles } from '../../lib/api/fetchFromDatabase';
	import type Article from '$lib/types/Article';

	let articles: Article[] = [];

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
	<article>
		<h2>{article.id}</h2>
		<p>{article.title}</p>
		<p>{article.content}</p>
	</article>
{/each}
