<script lang="ts">
	import { BASE_URL } from '$lib/api/api';
	import StaticPicture from '$lib/components/picture/StaticPicture.svelte';
	import type Author from '$lib/types/Author';
	import { onMount } from 'svelte';
	import type Article from '$lib/types/Article';
	import { fetchArticlesByAuthor } from '$lib/api/fetchFromDatabase';
	import ArticleCard from '../../../../lib/components/ArticleCard.svelte';

	export let data: { author: Author };

	let author: Author = data.author;

	let articles: Article[] = [];

	onMount(async () => {
		const response = await fetchArticlesByAuthor(author.urlSlug);
		articles = response as Article[];
	});
</script>

<svelte:head>
	<title>{author.firstName} | THE CAP</title>
	<meta name="description" content="{author.content}">
	<meta name="keywords" content="{author.firstName + author.lastName}">
</svelte:head>

<section class="container mt-14 flex flex-col items-center gap-10">
	<div class="flex md:flex-row flex-col items-center md:gap-6">
		<h1 class="text-center">{author.firstName + ' ' + author.lastName}</h1>
		<StaticPicture image="{BASE_URL}{author.image}" alt={author.firstName} width={200} height={200} imgClass="h-full object-cover rounded-full" class="w-32 h-32" />
	</div>
	<div class="flex justify-center">
		<p class="text-center md:w-3/4">{author.content}</p>
	</div>
	<div class="grid grid-cols-1 mt-12 md:grid-cols-2 lg:grid-cols-3 gap-8">
		{#if articles.length === 0}
			<p class="text-center">Autor zatím nemá žádné články.</p>
		{:else}
			{#each articles as article}
				<ArticleCard {article} />
			{/each}
		{/if}
	</div>
</section>
