<script lang="ts">
	import Icon from "$lib/components/Icon.svelte";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import { putAuthor } from "$lib/api/putDataToDatabase";
	import Modal from "$lib/components/Modal.svelte";
	import Logger from "$lib/components/Logger.svelte";
	import { deleteAuthor } from "$lib/api/deleteFromDatabase";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";
	import { uploadImage } from "$lib/api/uploadImage";
	import { BASE_URL } from "$lib/api/api";
	import arrow from "$lib/assets/icons/arrow.svg?raw";
	import type Author from "$lib/types/Author";

	export let data: { author: Author };

	let showLogger = false;
	let loggerMsg: string | unknown;
	let showModal = false;
	let type: 'success' | 'error';
	let selectedAuthor: Author | null = null;

	let author: Author = data.author;

	async function saveAuthor() {
		if (author.firstName === "") {
			showLogger = true;
			loggerMsg = "Jméno nesmí být prázdné.";
			type = 'error';
			return;
		}

		if (author.lastName === "") {
			showLogger = true;
			loggerMsg = "Příjmení nesmí být prázdné.";
			type = 'error';
			return;
		}

		if (author.email === "") {
			showLogger = true;
			loggerMsg = "Email nesmí být prázdný.";
			type = 'error';
			return;
		}

		try {
			const response = await putAuthor(author);
			if (response.ok) {
				showLogger = true;
				loggerMsg = "Autor byla úspěšně uložena.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/authors"), 2000);
			} else {
				showLogger = true;
				loggerMsg = `Email musí být unikátní - ${response.message}`;
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
				author.image = imageUrl;
			}
		}
	}

	function OpenDelete(author: Author) {
		selectedAuthor = author;
		showModal = true;
	}

	async function confirmDelete() {
		if (selectedAuthor && selectedAuthor.id) {
			if (await deleteAuthor(selectedAuthor.id)) {
				showLogger = true;
				loggerMsg = "Autor byla úspěšně smazaná.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/authors"), 2000);
			} else {
				showLogger = true;
				loggerMsg = "Nepodařilo se smazat Autora.";
			}
		}
		showModal = false;
	}

	function closeModal() {
		showModal = false;
		selectedAuthor = null;
	}

	function makeUrlSlug() {
		const randomValue = Math.floor(Math.random() * 10000);
		author.urlSlug = (randomValue + author.firstName + author.lastName).toLowerCase().replace(/ /g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
</script>

<div class="flex items-center py-8">
	<a href="/admin/authors" class="text-gray-500 text-xs hover:bg-gray-900 rounded-full hover:bg-opacity-5 transition-all duration-300 p-2">
		<Icon icon={arrow} class="w-4 h-4 rotate-180" />
	</a>
	<h1 class="font-light text-center p-0 my-0 text-4xl">Editace autora</h1>
</div>

<form on:submit|preventDefault={saveAuthor} class="flex flex-col md:grid grid-cols-2 gap-4">
	<label class="flex flex-col">
		Jméno
		<input type="text" bind:value={author.firstName} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		Příjmení
		<input type="text" bind:value={author.lastName} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col col-span-2">
		<span>
			UrlSlug
			<span class="text-3xs">(Automaticky generovaný)</span>
		</span>
		<input type="text" bind:value={author.urlSlug} disabled class="p-2 bg-gray-300" />
	</label>
	<label class="flex flex-col">
		Email
		<input type="email" bind:value={author.email} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		Telefonní číslo
		<input type="tel" bind:value={author.phoneNumber} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		Text
		<textarea bind:value={author.content} class="p-2 resize-none" rows="10" maxlength="1500" />
	</label>
	<div>
		{#if author.image !== null}
			<span class="flex items-center gap-2">
				<span>
					Obrázek
				</span>
				<button on:click={() => author.image = null}>
					<Icon icon={cross} class="w-4 h-4 hover:text-red-900 duration-300 transition-all" />
				</button>
			</span>
			<div class="w-full h-[16rem] bg-gray-300">
				<img src="{BASE_URL}{author.image}" alt="Náhled obrázku" class="object-contain h-full w-full" />
			</div>
		{:else}
			<label class="flex flex-col">
				Obrázek
				<input type="file" accept="image/png, image/jpeg, image/webp" on:change={handleFileUpload} class="p-2"  />
			</label>
		{/if}
	</div>
	<span class="col-span-2 w-full flex gap-4">
		<button type="submit" class="p-2 w-full bg-black text-white text-xs hover:bg-green-900 transition-all duration-300">Uložit</button>
		<button type="button" on:click={() => OpenDelete(author)} class="p-2">
			<Icon icon={deleteIcon} class="w-5 h-5 text-text hover:!text-red-900 duration-300 transition-all" />
		</button>
	</span>
</form>
{#if showModal}
	<Modal message="Opravdu chcete smazat Autora?" on:confirm={confirmDelete} on:close={closeModal} />
{/if}
{#if showLogger}
	<Logger message={loggerMsg} {type} on:close={() => showLogger = false} />
{/if}
