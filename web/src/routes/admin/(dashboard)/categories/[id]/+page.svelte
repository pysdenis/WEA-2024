<script lang="ts">
	import type Category from "$lib/types/Category";
	import Icon from "$lib/components/Icon.svelte";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import { putCategory } from "$lib/api/putDataToDatabase";
	import Modal from "$lib/components/Modal.svelte";
	import Logger from "$lib/components/Logger.svelte";
	import { deleteCategory } from "$lib/api/deleteFromDatabase";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";

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

		if (await putCategory(category)) {
			showLogger = true;
			loggerMsg = "Kategorie byla úspěšně uložena.";
			type = 'success';
			setTimeout(() => window.location.assign("/admin/categories"), 2000);
		} else {
			showLogger = true;
			loggerMsg = "Nepodařilo se uložit kategorii.";
			type = 'error';
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
		category.urlSlug = category.name.toLowerCase().replace(/ /g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
</script>

<h1 class="font-light text-center text-4xl">Editace kategorie</h1>

<form on:submit|preventDefault={saveCategory} class="flex flex-col md:grid grid-cols-2 gap-4">
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
		<!-- TODO: pridat nejaky content formatter, jestli zbyde cas -->
		<textarea bind:value={category.content} class="p-2 resize-none" rows="10" maxlength="1500" />
	</label>
	<div>
		{#if category.image !== null}
			<!-- TODO: fix až budou funkční obrázky -->
			<span class="flex items-center gap-2">
				<span>
					Obrázek
				</span>
				<button on:click={() => category.image = null}>
					<Icon icon={cross} class="w-4 h-4 hover:text-red-900 duration-300 transition-all" />
				</button>
			</span>
			<div class="w-full h-[16rem] bg-gray-300">
				<img src={category.image} alt="Náhled obrázku" class="object-contain" />
			</div>
		{:else}
			<label class="flex flex-col">
				Obrázek
				<input type="file" accept="image/png, image/jpeg, image/webp" bind:value={category.image} class="p-2"  />
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
