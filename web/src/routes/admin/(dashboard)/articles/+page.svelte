<script lang="ts">
	import { onMount } from "svelte";
	import { fetchArticles } from "$lib/api/fetchFromDatabase";
	import Icon from "$lib/components/Icon.svelte";
	import edit from "$lib/assets/icons/edit.svg?raw";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";
	import Modal from "$lib/components/Modal.svelte";
	import { deleteArticle } from "$lib/api/deleteFromDatabase";
	import Logger from '$lib/components/Logger.svelte';
	import type Article from "$lib/types/Article";
	import { localizeDate } from "$lib/scripts/date";

	let showLogger = false;
	let loggerMsg: string | unknown;
	let articles: Article[] = [];
	let showModal = false;
	let selectedArticle: Article | null = null;

	onMount(async () => {
		const response = await fetchArticles();
		articles = response as Article[];
	});

	function OpenDelete(article: Article) {
		selectedArticle = article;
		showModal = true;
	}

	async function confirmDelete() {
		if (selectedArticle && selectedArticle.id) {
			if (await deleteArticle(selectedArticle.id)) {
				articles = articles.filter(cat => cat.id !== selectedArticle?.id);
			} else {
				showLogger = true;
				loggerMsg = "Nepodařilo se smazat Článek.";
			}
		}
		showModal = false;
	}

	function closeModal() {
		showModal = false;
		selectedArticle = null;
	}
</script>

<div class="flex items-center py-8">
	<h1 class="font-light text-center p-0 my-0 text-4xl">Články</h1>
	<a href="/admin/articles/new" class="bg-black text-white text-xs hover:bg-green-900 transition-all duration-300 p-2">Přidat</a>
</div>

<div class="w-full flex-col flex gap-1 pb-4 overflow-y-auto px-3">
	<div class="grid grid-cols-3 lg:grid-cols-5 uppercase p-3 text-3xs md:text-2xs pb-3 border-b-2">
		<span>Název</span>
		<span>Publikován</span>
		<span class="hidden lg:inline-block">Kategorie</span>
		<span class="hidden lg:inline-block">Autor</span>
		<span class="text-end">Editovat</span>
	</div>
	{#each articles as article}
		<div class="grid grid-cols-3 lg:grid-cols-5 p-3">
			<span>{article.title}</span>
			<div title={new Date(article.publishedAt) < new Date() ? "Článek je publikován." : "Článek bude publikován, dle zvoleného data."}>
				<span class="{new Date(article.publishedAt) < new Date() ? "bg-green-200" : "bg-red-200"} p-1">{localizeDate(article.publishedAt)}</span>
			</div>
			<span class="hidden lg:inline-block">{article.categoryName}</span>
			<span class="hidden lg:inline-block">{article.authorName}</span>
			<span class="flex items-center gap-4 justify-end">
				<a class="w-5 h-5" href={`/admin/articles/${article.id}`}>
					<Icon icon={edit} class="w-5 h-5 text-text hover:!text-blue-900 duration-300 transition-all" />
				</a>
				<button on:click={() => OpenDelete(article)}>
					<Icon icon={deleteIcon} class="w-5 h-5 text-text hover:!text-red-900 duration-300 transition-all" />
				</button>
			</span>
		</div>
	{/each}
</div>
{#if showModal}
	<Modal message="Opravdu chcete smazat článek?" on:confirm={confirmDelete} on:close={closeModal} />
{/if}
{#if showLogger}
	<Logger message={loggerMsg} on:close={() => showLogger = false} />
{/if}
