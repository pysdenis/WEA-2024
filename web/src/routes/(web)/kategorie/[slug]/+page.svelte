<script lang="ts">
	import type Category from "$lib/types/Category";
	import { beforeUpdate, onMount } from "svelte";
	import { fetchArticles, fetchArticlesByCategory, fetchCategories } from "$lib/api/fetchFromDatabase";
	import { get } from "svelte/store";
	import { page } from "$app/stores";
	import type Article from '$lib/types/Article';
	import StaticPicture from "$lib/components/picture/StaticPicture.svelte";
	import { BASE_URL } from "$lib/api/api";
	import { localizeDate } from "$lib/scripts/date";

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

{#if category.image}
	<StaticPicture image="{BASE_URL}{category.image}" alt={category.name} width={1140} height={0} imgClass="object-cover w-full" class="w-full max-h-48 overflow-hidden" />
{/if}
<section class="container">
	<h1 class="text-center">{category.name}</h1>
	<p class="text-center mt-6">{category.content}</p>
	<div class="grid grid-cols-1 mt-12 md:grid-cols-2 lg:grid-cols-3 gap-8">
		{#if articles.length === 0}
			<p class="text-center">Žádné články</p>
		{:else}
			{#each articles as article}
				{#if new Date(article.publishedAt) <= date}
					<div class="bg-white shadow-md hover:scale-105 duration-300 overflow-hidden relative">
						<span class="absolute bg-primary text-2xs text-white py-1 px-2 right-0">{localizeDate(article.publishedAt)}</span>
						{#if article.image}
							<a href="/clanky/{article.urlSlug}">
								<StaticPicture image="{BASE_URL}{article.image}" alt={article.title} width={1140} height={0} imgClass="object-cover h-full w-full" class="w-full h-44 overflow-hidden" />
							</a>
						{/if}
						<div class="p-4 grid grid-rows-2">
							<a href="/clanky/{article.urlSlug}">
								<h2 class="text-xl font-bold">{article.title}</h2>
							</a>
							<p>{article.perex}</p>
						</div>
					</div>
				{/if}
			{/each}
		{/if}
	</div>
</section>
