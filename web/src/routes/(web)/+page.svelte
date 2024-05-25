<script lang="ts">
	import { onMount } from 'svelte';
	import { fetchData, type Article } from '$lib/api/api';

	let articles:Article[] = [];

	onMount(async () => {
		const response = await fetchData('articles');
		articles = response as unknown as Article[];
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
