<script lang="ts">
	import type Category from "$lib/types/Category";
	import { onMount } from "svelte";
	import { fetchCategories } from "$lib/api/fetchFromDatabase";
	import Icon from "$lib/components/Icon.svelte";
	import edit from "$lib/assets/icons/edit.svg?raw";
	import deleteIcon from "$lib/assets/icons/delete.svg?raw";
	import check from "$lib/assets/icons/check.svg?raw";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import Modal from "$lib/components/Modal.svelte";
	import { deleteCategory } from "$lib/api/deleteFromDatabase";
	import Logger from '$lib/components/Logger.svelte';

	let showLogger = false;
	let loggerMsg: string | unknown;
	let categories: Category[] = [];
	let showModal = false;
	let logType: "error" | "success" | undefined;
	let selectedCategory: Category | null = null;

	onMount(async () => {
		const response = await fetchCategories();
		categories = response as Category[];
	});

	function OpenDelete(category: Category) {
		selectedCategory = category;
		showModal = true;
	}

	async function confirmDelete() {
		if (selectedCategory && selectedCategory.id) {
			try {
				await deleteCategory(selectedCategory.id);
				categories = categories.filter((category) => category.id !== selectedCategory?.id);
				loggerMsg = "Kategorie byla úspěšně smazána";
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
		selectedCategory = null;
	}
</script>

<div class="flex items-center py-8">
	<h1 class="font-light text-center p-0 my-0 text-4xl">Kategorie</h1>
	<a href="/admin/categories/new" class="bg-black text-white text-xs hover:bg-green-900 transition-all duration-300 p-2">Přidat</a>
</div>

<div class="w-full flex-col flex gap-1 pb-4 overflow-y-auto px-3">
	<div class="grid grid-cols-5 uppercase p-3 text-3xs md:text-2xs pb-3 border-b-2">
		<span>ID</span>
		<span class="col-span-2">Název</span>
		<span class="text-center sm:text-end">V menu</span>
		<span class="text-end">Editovat</span>
	</div>
	{#each categories as category}
		<div class="grid grid-cols-5 p-3">
			<span>{category.id}</span>
			<span class="col-span-2">{category.name}</span>
			<span class="flex items-center justify-center sm:justify-end">
				{#if category.inMenu}
					<Icon icon={check} class="w-5 h-5 text-green-500" />
				{:else}
					<Icon icon={cross} class="w-5 h-5 text-red-500" />
				{/if}
			</span>
			<span class="flex items-center gap-4 justify-end">
				<a class="w-5 h-5" href={`/admin/categories/${category.id}`}>
					<Icon icon={edit} class="w-5 h-5 text-text hover:!text-blue-900 duration-300 transition-all" />
				</a>
				<button on:click={() => OpenDelete(category)}>
					<Icon icon={deleteIcon} class="w-5 h-5 text-text hover:!text-red-900 duration-300 transition-all" />
				</button>
			</span>
		</div>
	{/each}
</div>
{#if showModal}
	<Modal message="Opravdu chcete smazat kategorii a všechny články pod touhle kategorií?" on:confirm={confirmDelete} on:close={closeModal} />
{/if}
{#if showLogger}
	<Logger message={loggerMsg} type={logType} on:close={() => showLogger = false} />
{/if}
