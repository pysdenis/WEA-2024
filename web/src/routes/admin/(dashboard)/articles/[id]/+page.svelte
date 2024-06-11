<script lang="ts">
	import type Article from "$lib/types/Article";
	import Icon from "$lib/components/Icon.svelte";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import { putArticle } from "$lib/api/putDataToDatabase";
	import Modal from "$lib/components/Modal.svelte";
	import Logger from "$lib/components/Logger.svelte";
	import { deleteCategory } from "$lib/api/deleteFromDatabase";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";
	import { uploadImage } from "$lib/api/uploadImage";
	import { BASE_URL } from "$lib/api/api";
	import arrow from "$lib/assets/icons/arrow.svg?raw";
	import { onMount } from "svelte";
	import { fetchAuthors, fetchCategories } from "$lib/api/fetchFromDatabase";
	import type Category from '$lib/types/Category';
	import type Author from '$lib/types/Author';

	export let data: { article: Article };

	let showLogger = false;
	let loggerMsg: string | unknown;
	let showModal = false;
	let type: 'success' | 'error';
	let selectedArticle: Article | null = null;

	let article: Article = data.article;
	let categories: Category[] = [];
	let authors: Author[] = [];

	onMount(async () => {
		const categoryResponse = await fetchCategories();
		categories = categoryResponse as Category[];

		const authorsResponse = await fetchAuthors();
		authors = authorsResponse as Author[];
	});

	async function saveArticle() {
		if (!article.title || !article.urlSlug || !article.publishedAt || !article.content || !article.categoryId || !article.authorId || !article.image) {
			showLogger = true;
			loggerMsg = "Všechna pole musí být vyplněna.";
			type = 'error';
			return;
		}

		article.createdAt = new Date().toISOString();
		article.perex = article.content.substring(0, 200);

		try {
			const response = await putArticle(article);
			if (response.ok) {
				showLogger = true;
				loggerMsg = "Článek byl úspěšně uložen.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/articles"), 2000);
			} else {
				showLogger = true;
				loggerMsg = `Název, urlSlug musí být unikátní - ${response.message}`;
				type = 'error';
			}
		} catch (error) {
			showLogger = true;
			loggerMsg = error;
			type = 'error';
		}
	}

	async function handleFileUpload(event: Event) {
		const target = event.target as HTMLInputElement;
		const file = target.files?.[0];
		if (file) {
			if (file.size > 10 * 1024 * 1024) {
				showLogger = true;
				loggerMsg = `Obrázek je příliš velký. Maximální povolená velikost je 10MB. Vložený obrázek má ${Math.round(file.size / (1024 * 1024))}MB.`;
				type = 'error';
				return;
			}
			if (!file.type.startsWith('image/')) {
				showLogger = true;
				loggerMsg = 'Vložený soubor není obrázek.';
				type = 'error';
				return;
			}
			const formData = new FormData();
			formData.append('image', file);
			const imageUrl = await uploadImage(formData);
			if (imageUrl) {
				article.image = imageUrl;
			}
		}
	}

	function OpenDelete(article: Article) {
		selectedArticle = article;
		showModal = true;
	}

	async function confirmDelete() {
		if (selectedArticle && selectedArticle.id) {
			if (await deleteCategory(selectedArticle.id)) {
				showLogger = true;
				loggerMsg = "Článek byl úspěšně smazán.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/articles"), 2000);
			} else {
				showLogger = true;
				loggerMsg = "Nepodařilo se smazat článek.";
			}
		}
		showModal = false;
	}

	function closeModal() {
		showModal = false;
		selectedArticle = null;
	}

	function makeUrlSlug() {
		article.urlSlug = article.title.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
</script>

<div class="flex items-center py-8">
	<a href="/admin/articles" class="text-gray-500 text-xs hover:bg-gray-900 rounded-full hover:bg-opacity-5 transition-all duration-300 p-2">
		<Icon icon={arrow} class="w-4 h-4 rotate-180" />
	</a>
	<h1 class="font-light text-center p-0 my-0 text-4xl">Editace článku</h1>
</div>

<form on:submit|preventDefault={saveArticle} class="flex flex-col overflow-y-auto md:grid grid-cols-2 gap-4">
	<label class="flex flex-col">
		Název
		<input type="text" bind:value={article.title} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		<span>
			UrlSlug
			<span class="text-3xs">(Generovaný z názvu)</span>
		</span>
		<input type="text" bind:value={article.urlSlug} disabled class="p-2 bg-gray-300" />
	</label>
	<label class="flex flex-col">
		Datum publikace
		<input type="date" bind:value={article.publishedAt} class="p-2" on:input={makeUrlSlug} />
	</label>
	<div>
		{#if article.image !== null}
			<span class="flex items-center gap-2">
				<span>
					Obrázek
				</span>
				<button on:click={() => article.image = null}>
					<Icon icon={cross} class="w-4 h-4 hover:text-red-900 duration-300 transition-all" />
				</button>
			</span>
			<div class="w-full h-[16rem] bg-gray-300">
				<img src="{BASE_URL}{article.image}" alt="Náhled obrázku" class="object-contain h-full w-full" />
			</div>
		{:else}
			<label class="flex flex-col">
				Obrázek
				<input type="file" accept="image/png, image/jpeg, image/webp" on:change={handleFileUpload} class="p-2"  />
			</label>
		{/if}
	</div>
	<label class="flex flex-col col-span-2">
		Text
		<textarea bind:value={article.content} class="p-2 resize-none" rows="10" maxlength="1500" />
	</label>
	<label class="flex flex-col">
		Kategorie
		<select bind:value={article.categoryId} class="p-2">
			{#each categories as category}
				<option value={category.id}>{category.name}</option>
			{/each}
		</select>
	</label>
	<label class="flex flex-col">
		Author
		<select bind:value={article.authorId} class="p-2">
			{#each authors as author}
				{#if String(article.id) === String(article.authorId)}
					<option value={author.id} selected>{author.firstName + ' ' +  author.lastName}</option>
				{:else}
					<option value={author.id}>{author.firstName + ' ' +  author.lastName}</option>
				{/if}
			{/each}
		</select>
	</label>
	<span class="col-span-2 w-full flex gap-4">
		<button type="submit" class="p-2 w-full bg-black text-white text-xs hover:bg-green-900 transition-all duration-300">Uložit</button>
		<button type="button" on:click={() => OpenDelete(article)} class="p-2">
			<Icon icon={deleteIcon} class="w-5 h-5 text-text hover:!text-red-900 duration-300 transition-all" />
		</button>
	</span>
</form>
{#if showModal}
	<Modal message="Opravdu chcete smazat článek?" on:confirm={confirmDelete} on:close={closeModal} />
{/if}
{#if showLogger}
	<Logger message={loggerMsg} {type} on:close={() => showLogger = false} />
{/if}
