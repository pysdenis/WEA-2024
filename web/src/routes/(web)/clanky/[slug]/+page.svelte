<script lang="ts">
	import type Article from '$lib/types/Article';
	import { BASE_URL } from '$lib/api/api';
	import StaticPicture from '$lib/components/picture/StaticPicture.svelte';
	import { localizeDate } from '$lib/scripts/date';
	import { beforeUpdate, onMount } from 'svelte';
	import { fetchArticles } from '../../../../lib/api/fetchFromDatabase';
	import SmallArticleCard from '../../../../lib/components/SmallArticleCard.svelte';
	import { page } from '$app/stores';
	import { get } from 'svelte/store';

	export let data: { article: Article };

	let article: Article = data.article;
	let recommendedArticles: Article[] = [];

	let slug: string;

	const loadNewArticle = async () => {
		const articlesResponse = await fetchArticles() as Article[];
		recommendedArticles = articlesResponse.filter(a => a.id !== article.id).reverse().slice(0, 4);

		const response = await fetchArticles(slug);
		article = response as Article;
	};

	onMount(async () => {
		slug = get(page).params.slug;
		loadNewArticle();
	});

	beforeUpdate(() => {
		const newSlug = get(page).params.slug;
		if (newSlug !== slug) {
			slug = newSlug;
			loadNewArticle();
		}
	});
</script>

<svelte:head>
	<title>{article.title} | THE CAP</title>
	<meta name="description" content="{article.content}">
	<meta name="keywords" content="{article.title}">
</svelte:head>

<section class="flex container flex-col gap-8 lg:flex-row">
	<article class="lg:w-3/4">
		<h1>{article.title}</h1>
		<StaticPicture image="{BASE_URL}{article.image}" alt={article.title} width={1140} height={0} imgClass="object-cover w-full" class="w-full" />
		<div class="flex justify-start text-3xs md:text-2xs">
			<span>Publikováno: {localizeDate(article.publishedAt)}</span>
		</div>
		<p class="mt-2 leading-7 text-justify md:text-md">{article.content}</p>
	</article>
	<aside class="lg:w-1/4 mt-6 flex flex-col items-center md:mt-0">
		<div class="flex flex-col gap-4 md:pt-[6.6rem]">
			<div>
				<a href="/autor/{article.authorUrlSlug}" class="p-2 flex text-white bg-primary w-full shadow-md hover:scale-105 duration-300 justify-between items-center">
					<span>Autor článku</span>
					<span>–</span>
					<span>{article.authorName}</span>
				</a>
			</div>
			<span class="text-xl text-center uppercase">Doporučené články</span>
			<div class="flex flex-col mt-2 gap-4">
				{#each recommendedArticles as article }
					<SmallArticleCard {article} />
				{/each}
			</div>
		</div>
	</aside>
</section>
