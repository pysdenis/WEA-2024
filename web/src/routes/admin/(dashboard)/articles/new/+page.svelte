<script lang="ts">
	import Icon from "$lib/components/Icon.svelte";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import Logger from "$lib/components/Logger.svelte";
	import { postArticle } from "$lib/api/postDataToDatabase";
	import { uploadImage } from "$lib/api/uploadImage";
	import { BASE_URL } from "$lib/api/api";
	import arrow from "$lib/assets/icons/arrow.svg?raw";
	import { fetchAuthors, fetchCategories } from "$lib/api/fetchFromDatabase";
	import { onMount } from "svelte";
	import type Author from "$lib/types/Author";
	import type Category from "$lib/types/Category";

	let showLogger = false;
	let loggerMsg: string | unknown;
	let type: 'success' | 'error';

	let title = "";
	let content = "";
	let urlSlug = "";
	let categoryId = 0;
	let authorId = 0;
	let publishedAt = new Date().toISOString();
	let createdAt = new Date().toISOString();
	let perex = "";
	let image: string | null = null;
	let categories: Category[] = [];
	let authors: Author[] = [];

	onMount(async () => {
		const categoryResponse = await fetchCategories();
		categories = categoryResponse as Category[];

		const authorsResponse = await fetchAuthors();
		authors = authorsResponse as Author[];
	});

	async function saveArticle() {
		perex = content.substring(0, 200);

		if (!title || !urlSlug || !publishedAt || !content || !categoryId || !authorId) {
			showLogger = true;
			loggerMsg = "Všechna pole musí být vyplněna.";
			type = 'error';
			return;
		}

		try {
			const response = await postArticle({ title, content, urlSlug, categoryId, authorId, publishedAt, createdAt, perex, image });
			if (response.ok) {
				showLogger = true;
				loggerMsg = "Kategorie byla úspěšně vytvořena.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/articles"), 2000);
			} else {
				showLogger = true;
				loggerMsg = `Název a urlSlug musí být unikátní - ${response.message}`;
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
				image = imageUrl;
			}
		}
	}

	function makeUrlSlug() {
		urlSlug = title.toLowerCase().replace(/ /g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
</script>

<div class="flex items-center py-8">
	<a href="/admin/articles" class="text-gray-500 text-xs hover:bg-gray-900 rounded-full hover:bg-opacity-5 transition-all duration-300 p-2">
		<Icon icon={arrow} class="w-4 h-4 rotate-180" />
	</a>
	<h1 class="font-light text-center p-0 my-0 text-4xl">Přídání nového článku</h1>
</div>

<form on:submit|preventDefault={saveArticle} class="flex flex-col md:grid grid-cols-2 gap-4">
	<label class="flex flex-col">
		Název
		<input type="text" bind:value={title} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		<span>
			UrlSlug
			<span class="text-3xs">(Generovaný z názvu)</span>
		</span>
		<input type="text" bind:value={urlSlug} disabled class="p-2 bg-gray-300" />
	</label>
	<label class="flex flex-col">
		Datum publikace
		<input type="date" bind:value={publishedAt} class="p-2" on:input={makeUrlSlug} />
	</label>
	<div>
		{#if image !== null}
			<span class="flex items-center gap-2">
				<span>
					Obrázek
				</span>
				<button on:click={() => image = null}>
					<Icon icon={cross} class="w-4 h-4 hover:text-red-900 duration-300 transition-all" />
				</button>
			</span>
			<div class="w-full h-[16rem] bg-gray-300">
				<img src="{BASE_URL}{image}" alt="Náhled obrázku" class="object-contain h-full w-full" />
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
		<textarea bind:value={content} class="p-2 resize-none" rows="10" maxlength="1500" />
	</label>
	<label class="flex flex-col">
		Kategorie
		<select bind:value={categoryId} class="p-2">
			{#each categories as category}
				<option value={category.id}>{category.name}</option>
			{/each}
		</select>
	</label>
	<label class="flex flex-col">
		Author
		<select bind:value={authorId} class="p-2">
			{#each authors as author}
				<option value={author.id}>{author.firstName + ' ' +  author.lastName}</option>
			{/each}
		</select>
	</label>
	<span class="col-span-2 w-full flex gap-4">
		<button type="submit" class="p-2 w-full bg-black text-white text-xs hover:bg-green-900 transition-all duration-300">Uložit</button>
	</span>
</form>
{#if showLogger}
	<Logger message={loggerMsg} {type} on:close={() => showLogger = false} />
{/if}
