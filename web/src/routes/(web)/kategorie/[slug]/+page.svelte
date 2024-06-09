<script lang="ts">
	import ArticleCard from './../../../../lib/components/ArticleCard.svelte';
	import type Category from "$lib/types/Category";
	import { beforeUpdate, onMount } from "svelte";
	import { fetchArticlesByCategory, fetchCategories } from "$lib/api/fetchFromDatabase";
	import { get } from "svelte/store";
	import { page } from "$app/stores";
	import type Article from '$lib/types/Article';
	import StaticPicture from "$lib/components/picture/StaticPicture.svelte";
	import { BASE_URL } from "$lib/api/api";

	export let data: { category: Category };

	let category: Category = data.category;
	let articles: Article[] = [];
	let slug: string;
	let date = new Date();

	const loadCategoryAndArticles = async () => {
		const categoryResponse = await fetchCategories(slug);
		category = categoryResponse as Category;

		const articlesResponse = await fetchArticlesByCategory(slug);
		articles = articlesResponse as Article[];
	};

	onMount(async () => {
		slug = get(page).params.slug;
		loadCategoryAndArticles();
	});

	beforeUpdate(() => {
		const newSlug = get(page).params.slug;
		if (newSlug !== slug) {
			slug = newSlug;
			loadCategoryAndArticles();
		}
	});
</script>

<svelte:head>
	<title>{category.name} | THE CAP</title>
	<meta name="description" content="{category.content}">
	<meta name="keywords" content="{category.name}">
</svelte:head>

{#if category.image}
	<StaticPicture image="{BASE_URL}{category.image}" alt={category.name} width={1140} height={0} imgClass="object-cover w-full" class="w-full max-h-56 overflow-hidden" />
{/if}
<section class="container">
	<h1 class="text-center">{category.name}</h1>
	<p class="text-center mt-6">{category.content}</p>
	<div class="grid grid-cols-1 mt-12 md:grid-cols-2 lg:grid-cols-3 gap-8">
		{#if articles.length === 0}
			<p class="text-center">Žádné články</p>
		{:else}
			{#each articles as article}
				<ArticleCard {article} date={date} />
			{/each}
		{/if}
	</div>
</section>
