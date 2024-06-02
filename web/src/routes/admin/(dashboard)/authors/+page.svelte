<script lang="ts">
	import type Author from "$lib/types/Author";
	import { onMount } from "svelte";
	import { fetchAuthors } from "$lib/api/fetchFromDatabase";
	import Icon from "$lib/components/Icon.svelte";
	import edit from "$lib/assets/icons/edit.svg?raw";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";
	import Modal from "$lib/components/Modal.svelte";
	import { deleteAuthor } from "$lib/api/deleteFromDatabase";
	import Logger from '$lib/components/Logger.svelte';

	let showLogger = false;
	let loggerMsg: string | unknown;
	let authors: Author[] = [];
	let showModal = false;
	let logType: "error" | "success" | undefined;
	let selectedAuthor: Author | null = null;

	onMount(async () => {
		const response = await fetchAuthors();
		authors = response as Author[];
	});

	function OpenDelete(Author: Author) {
		selectedAuthor = Author;
		showModal = true;
	}

	async function confirmDelete() {
		if (selectedAuthor && selectedAuthor.id) {
			try {
				await deleteAuthor(selectedAuthor.id);
				authors = authors.filter((author) => author.id !== selectedAuthor?.id);
				loggerMsg = "Autor byl úspěšně smazán";
				logType = "success";
				showLogger = true;
			} catch (error) {
				loggerMsg = "Něco se pokazilo, zkuste to prosím znovu";
				logType = "error";
				showLogger = true;
			}
		}
		showModal = false;
	}

	function closeModal() {
		showModal = false;
		selectedAuthor = null;
	}
</script>

<div class="flex items-center py-8">
	<h1 class="font-light text-center p-0 my-0 text-4xl">Autoři</h1>
	<a href="/admin/authors/new" class="bg-black text-white text-xs hover:bg-green-900 transition-all duration-300 p-2">Přidat</a>
</div>

<div class="w-full flex-col flex gap-1 pb-4 overflow-y-auto px-3">
	<div class="grid grid-cols-3 lg:grid-cols-4 uppercase p-3 text-3xs md:text-2xs pb-3 border-b-2">
		<span>Jméno a Příjmení</span>
		<span class="hidden lg:inline-block">email</span>
		<span class="text-center">Telefonní číslo</span>
		<span class="text-end">Editovat</span>
	</div>
	{#each authors as author}
		<div class="grid grid-cols-3 lg:grid-cols-4 p-3">
			<span>{author.firstName} {author.lastName}</span>
			<span class="hidden lg:inline-block">{author.email}</span>
			<span class="text-center">{author.phoneNumber}</span>
			<span class="flex items-center gap-4 justify-end">
				<a class="w-5 h-5" href={`/admin/authors/${author.id}`}>
					<Icon icon={edit} class="w-5 h-5 text-text hover:!text-blue-900 duration-300 transition-all" />
				</a>
				<button on:click={() => OpenDelete(author)}>
					<Icon icon={deleteIcon} class="w-5 h-5 text-text hover:!text-red-900 duration-300 transition-all" />
				</button>
			</span>
		</div>
	{/each}
</div>
{#if showModal}
	<Modal message="Opravdu chcete smazat Autora a všechny články pod tímhle autorem?" on:confirm={confirmDelete} on:close={closeModal} />
{/if}
{#if showLogger}
	<Logger message={loggerMsg} type={logType} on:close={() => showLogger = false} />
{/if}
