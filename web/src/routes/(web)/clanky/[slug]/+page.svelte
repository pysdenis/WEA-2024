<script lang="ts">
	import type Article from '$lib/types/Article';
	import { BASE_URL } from '$lib/api/api';
	import StaticPicture from '$lib/components/picture/StaticPicture.svelte';
	import { localizeDate } from '$lib/scripts/date';

	export let data: { article: Article };

	let article: Article = data.article;

	console.log(article.authorUrlSlug);
</script>

<section class="flex container flex-col gap-8 md:flex-row">
	<article class="md:w-3/4">
		<h1>{article.title}</h1>
		<StaticPicture image="{BASE_URL}{article.image}" alt={article.title} width={1140} height={0} imgClass="object-cover w-full" class="w-full" />
		<div class="flex justify-between text-3xs md:text-2xs">
			<span>Publikováno: {localizeDate(article.publishedAt)}</span>
			<span>
				<span>Autor:</span>
				<a href="/autor/{article.authorUrlSlug}">{article.authorName}</a>
			</span>
		</div>
		<p class="mt-2 leading-7 text-justify md:text-md">{article.content}</p>
	</article>
	<aside class="md:w-1/4 mt-6 flex flex-col items-center md:mt-0">
		<span class="text-xl text-center md:mt-[6.3rem] uppercase">Další články</span>
	</aside>
</section>
