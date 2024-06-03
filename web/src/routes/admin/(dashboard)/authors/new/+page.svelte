<script lang="ts">
	import Icon from "$lib/components/Icon.svelte";
	import cross from "$lib/assets/icons/cross.svg?raw";
	import Logger from "$lib/components/Logger.svelte";
	import { postAuthor } from "$lib/api/postDataToDatabase";
	import { uploadImage } from "$lib/api/uploadImage";
	import { BASE_URL } from "$lib/api/api";
	import arrow from "$lib/assets/icons/arrow.svg?raw";

	let showLogger = false;
	let loggerMsg: string | unknown;
	let type: 'success' | 'error';

	let firstName = "";
	let lastName = "";
	let email = "";
	let phoneNumber = "";
	let content = "";
	let urlSlug = "";
	let image: string | null = null;

	async function saveAuthor() {
		if (firstName === "") {
			showLogger = true;
			loggerMsg = "Jméno nesmí být prázdné.";
			type = 'error';
			return;
		}

		if (lastName === "") {
			showLogger = true;
			loggerMsg = "Příjmení nesmí být prázdné.";
			type = 'error';
			return;
		}

		if (email === "") {
			showLogger = true;
			loggerMsg = "Email nesmí být prázdný.";
			type = 'error';
			return;
		}

		try {
			const response = await postAuthor({ firstName, lastName, email, phoneNumber, content, urlSlug, image });
			if (response.ok) {
				showLogger = true;
				loggerMsg = "Autor byl úspěšně vytvořen.";
				type = 'success';
				setTimeout(() => window.location.assign("/admin/authors"), 2000);
			} else {
				showLogger = true;
				loggerMsg = `Email a urlSlug musí být unikátní - ${response.message}`;
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
		const randomValue = Math.floor(Math.random() * 10000);
		urlSlug = (randomValue + firstName + lastName).toLowerCase().replace(/ /g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
</script>

<div class="flex items-center py-8">
	<a href="/admin/authors" class="text-gray-500 text-xs hover:bg-gray-900 rounded-full hover:bg-opacity-5 transition-all duration-300 p-2">
		<Icon icon={arrow} class="w-4 h-4 rotate-180" />
	</a>
	<h1 class="font-light text-center p-0 my-0 text-4xl">Přídání nového autora</h1>
</div>

<form on:submit|preventDefault={saveAuthor} class="flex overflow-y-auto flex-col md:grid grid-cols-2 gap-4">
	<label class="flex flex-col">
		Jméno
		<input type="text" bind:value={firstName} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		Příjmení
		<input type="text" bind:value={lastName} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col col-span-2">
		<span>
			UrlSlug
			<span class="text-3xs">(Automaticky generovaný)</span>
		</span>
		<input type="text" bind:value={urlSlug} disabled class="p-2 bg-gray-300" />
	</label>
	<label class="flex flex-col">
		Email
		<input type="email" bind:value={email} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		Telefonní číslo
		<input type="tel" bind:value={phoneNumber} class="p-2" on:input={makeUrlSlug} />
	</label>
	<label class="flex flex-col">
		Text
		<textarea bind:value={content} class="p-2 resize-none" rows="10" maxlength="1500" />
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
	<span class="col-span-2 w-full flex gap-4">
		<button type="submit" class="p-2 w-full bg-black text-white text-xs hover:bg-green-900 transition-all duration-300">Uložit</button>
	</span>
</form>
{#if showLogger}
	<Logger message={loggerMsg} {type} on:close={() => showLogger = false} />
{/if}
