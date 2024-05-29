<script lang="ts">
	import type Category from "$lib/types/Category";
	import Icon from "$lib/components/Icon.svelte";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import { putCategory } from "$lib/api/putDataToDatabase";
	import Modal from "$lib/components/Modal.svelte";
	import Logger from "$lib/components/Logger.svelte";
	import { deleteCategory } from "$lib/api/deleteFromDatabase";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";
	import { postCategory } from "../../../../../lib/api/postDataToDatabase";

	let showLogger = false;
	let loggerMsg: string | unknown;
	let type: 'success' | 'error';

	let name = "";
	let content = "";
	let urlSlug = "";
	let image: string | null = null;
	let inMenu = false;

	async function saveCategory() {
		if (name === "") {
			showLogger = true;
			loggerMsg = "Název kategorie nesmí být prázdný.";
			type = 'error';
			return;
		}

		if (name.length > 25) {
			showLogger = true;
			loggerMsg = "Název kategorie nesmí být delší než 25 znaků.";
			type = 'error';
			return;
		}

		try {
			const response = await postCategory({ name, content, urlSlug, image, inMenu });
			if (response.ok) {
				showLogger = true;
				loggerMsg = "Kategorie byla úspěšně vytvořena.";
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

	function makeUrlSlug() {
		urlSlug = name.toLowerCase().replace(/ /g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
</script>

<h1 class="font-light text-center text-4xl">Přídání nové kategorie</h1>

<form on:submit|preventDefault={saveCategory} class="flex flex-col md:grid grid-cols-2 gap-4">
	<label class="flex flex-col">
		Název
		<input type="text" bind:value={name} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		<span>
			UrlSlug
			<span class="text-3xs">(Generovaný z názvu)</span>
		</span>
		<input type="text" bind:value={urlSlug} disabled class="p-2 bg-gray-300" />
	</label>
	<label class="flex flex-col">
		Text
		<!-- TODO: pridat nejaky content formatter, jestli zbyde cas -->
		<textarea bind:value={content} class="p-2 resize-none" rows="10" maxlength="1500" />
	</label>
	<div>
		{#if image !== null}
			<!-- TODO: fix až budou funkční obrázky -->
			<span class="flex items-center gap-2">
				<span>
					Obrázek
				</span>
				<button on:click={() => image = null}>
					<Icon icon={cross} class="w-4 h-4 hover:text-red-900 duration-300 transition-all" />
				</button>
			</span>
			<div class="w-full h-[16rem] bg-gray-300">
				<img src={image} alt="Náhled obrázku" class="object-contain" />
			</div>
		{:else}
			<label class="flex flex-col">
				Obrázek
				<input type="file" accept="image/png, image/jpeg, image/webp" bind:value={image} class="p-2"  />
			</label>
		{/if}
	</div>
	<label class="flex flex-row gap-2 items-center">
		Zobrazit v menu
		<input type="checkbox" bind:checked={inMenu} />
	</label>
	<span class="col-span-2 w-full flex gap-4">
		<button type="submit" class="p-2 w-full bg-black text-white text-xs hover:bg-green-900 transition-all duration-300">Uložit</button>
	</span>
</form>
{#if showLogger}
	<Logger message={loggerMsg} {type} on:close={() => showLogger = false} />
{/if}
