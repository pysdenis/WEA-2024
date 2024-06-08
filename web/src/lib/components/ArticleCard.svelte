<script lang="ts">
	import { BASE_URL } from "../api/api";
	import { localizeDate } from "../scripts/date";
	import type Article from "../types/Article";
	import StaticPicture from "./picture/StaticPicture.svelte";

	export let article: Article;
	export let date = new Date();

	function shortenText(text: string): string {
		if (text.length <= 166) return text;
		return text.slice(0, 166) + "...";
	}
</script>

{#if new Date(article.publishedAt) <= date}
	<div class="bg-white shadow-md hover:scale-105 duration-300 overflow-hidden relative">
		<span class="absolute bg-primary text-2xs text-white py-1 px-2 right-0">{localizeDate(article.publishedAt)}</span>
		{#if article.image}
			<a href="/clanky/{article.urlSlug}">
				<StaticPicture image="{BASE_URL}{article.image}" alt={article.title} width={1140} height={0} imgClass="object-cover h-full w-full" class="w-full h-44 overflow-hidden" />
			</a>
		{/if}
		<div class="p-4 grid gap-2 grid-rows-3">
			<span>
				<a href="/clanky/{article.urlSlug}">
					<h2 class="text-xl m-0 font-bold text-primary">{article.title}</h2>
				</a>
				<span class="text-gray-500 text-2xs">{article.authorName}</span>
			</span>
			<p class="row-span-2 text-2xs">{shortenText(article.perex)}</p>
		</div>
	</div>
{/if}
