<script lang="ts">
	import type Category from "$lib/types/Category";
	import Icon from "$lib/components/Icon.svelte";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import { putCategory } from "$lib/api/putDataToDatabase";
	import Modal from "$lib/components/Modal.svelte";
	import Logger from "$lib/components/Logger.svelte";
	import { deleteCategory } from "$lib/api/deleteFromDatabase";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";
	import { uploadImage } from "$lib/api/uploadImage";
	import { BASE_URL } from "$lib/api/api";
	import arrow from "$lib/assets/icons/arrow.svg?raw";

	export let data: { category: Category };

	let showLogger = false;
	let loggerMsg: string | unknown;
	let showModal = false;
	let type: 'success' | 'error';
	let selectedCategory: Category | null = null;

	let category: Category = data.category;

	async function saveCategory() {
		if (category.name === "") {
			showLogger = true;
			loggerMsg = "Název kategorie nesmí být prázdný.";
			type = 'error';
			return;
		}

		if (category.name.length > 25) {
			showLogger = true;
			loggerMsg = "Název kategorie nesmí být delší než 25 znaků.";
			type = 'error';
			return;
		}

		if (category.content === "") {
			showLogger = true;
			loggerMsg = "Text kategorie nesmí být prázdný.";
			type = 'error';
			return;
		}

		if (category.image === null) {
			showLogger = true;
			loggerMsg = "Obrázek kategorie nesmí být prázdný.";
			type = 'error';
			return;
		}

		try {
			const response = await putCategory(category);
			if (response.ok) {
				showLogger = true;
				loggerMsg = "Kategorie byla úspěšně uložena.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/categories"), 2000);
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
				category.image = imageUrl;
			}
		}
	}

	function OpenDelete(category: Category) {
		selectedCategory = category;
		showModal = true;
	}

	async function confirmDelete() {
		if (selectedCategory && selectedCategory.id) {
			if (await deleteCategory(selectedCategory.id)) {
				showLogger = true;
				loggerMsg = "Kategorie byla úspěšně smazaná.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/categories"), 2000);
			} else {
				showLogger = true;
				loggerMsg = "Nepodařilo se smazat kategorii.";
			}
		}
		showModal = false;
	}

	function closeModal() {
		showModal = false;
		selectedCategory = null;
	}

	function makeUrlSlug() {
		category.urlSlug = category.name.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
</script>

<div class="flex items-center py-8">
	<a href="/admin/categories" class="text-gray-500 text-xs hover:bg-gray-900 rounded-full hover:bg-opacity-5 transition-all duration-300 p-2">
		<Icon icon={arrow} class="w-4 h-4 rotate-180" />
	</a>
	<h1 class="font-light text-center p-0 my-0 text-4xl">Editace kategorie</h1>
</div>

<form on:submit|preventDefault={saveCategory} class="flex flex-col overflow-y-auto md:grid grid-cols-2 gap-4">
	<label class="flex flex-col">
		Název
		<input type="text" bind:value={category.name} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		<span>
			UrlSlug
			<span class="text-3xs">(Generovaný z názvu)</span>
		</span>
		<input type="text" bind:value={category.urlSlug} disabled class="p-2 bg-gray-300" />
	</label>
	<label class="flex flex-col">
		Text
		<textarea bind:value={category.content} class="p-2 resize-none" rows="10" maxlength="1500" />
	</label>
	<div>
		{#if category.image !== null}
			<span class="flex items-center gap-2">
				<span>
					Obrázek
				</span>
				<button on:click={() => category.image = null}>
					<Icon icon={cross} class="w-4 h-4 hover:text-red-900 duration-300 transition-all" />
				</button>
			</span>
			<div class="w-full h-[16rem] bg-gray-300">
				<img src="{BASE_URL}{category.image}" alt="Náhled obrázku" class="object-contain h-full w-full" />
			</div>
		{:else}
			<label class="flex flex-col">
				Obrázek
				<input type="file" accept="image/png, image/jpeg, image/webp" on:change={handleFileUpload} class="p-2"  />
			</label>
		{/if}
	</div>
	<label class="flex flex-row gap-2 items-center">
		Zobrazit v menu
		<input type="checkbox" bind:checked={category.inMenu} />
	</label>
	<span class="col-span-2 w-full flex gap-4">
		<button type="submit" class="p-2 w-full bg-black text-white text-xs hover:bg-green-900 transition-all duration-300">Uložit</button>
		<button type="button" on:click={() => OpenDelete(category)} class="p-2">
			<Icon icon={deleteIcon} class="w-5 h-5 text-text hover:!text-red-900 duration-300 transition-all" />
		</button>
	</span>
</form>
{#if showModal}
	<Modal message="Opravdu chcete smazat kategorii?" on:confirm={confirmDelete} on:close={closeModal} />
{/if}
{#if showLogger}
	<Logger message={loggerMsg} {type} on:close={() => showLogger = false} />
{/if}
