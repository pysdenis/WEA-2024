<script lang="ts">
	import Icon from './../../lib/components/Icon.svelte';
	import '$lib/assets/css/main.css';
	import logo from '$lib/assets/images/logo.png';
	import { fetchCategories } from '$lib/api/fetchFromDatabase';
	import { onMount } from 'svelte';
	import type Category from '$lib/types/Category';
	import menu from '$lib/assets/icons/menu.svg?raw';
	import cross from '$lib/assets/icons/cross.svg?raw';
	import Header from '../../lib/components/Header.svelte';

	let categories: Category[] = [];
	let showOthers = false;
	let windowScrollY = 0;

	onMount(async () => {
		const response = await fetchCategories();
		categories = response as Category[];
	});
</script>



<Header {categories} />

<main class="min-h-[88dvh] mt-24">
	<slot />
</main>
<footer class="flex items-center justify-center bg-secondary">
	<div class="container py-11 mb-5">
		<div class="lg:grid flex flex-col gap-6 lg:gap-12 lg:grid-cols-3">
			<div class="flex items-center gap-3 text-white">
				<a href="/">
					<img src={logo} height={70} width={70} alt="Logo THE CAP" />
				</a>
				<span class="text-xs">- For the meme culture</span>
			</div>
			<div class="flex flex-col gap-2">
				<h3 class="text-white text-2xl font-bold uppercase m-0 mb-1">Kontakt</h3>
				<p class="text-white">Telefon:
					<a href="tel:+420123456789">
						+420 123 456 789
					</a>
				</p>
				<p class="text-white">Email:
					<a href="mailto:info@thecap.cz">
						info@thecap.cz
					</a>
				</p>
			</div>
			<div class="flex flex-col gap-2">
				<h3 class="text-white text-2xl font-bold uppercase m-0 mb-1">Odkazy</h3>
				<div class="grid grid-cols-2 gap-2">
					{#each categories as category}
						<span class="flex">
							<a class="text-white" href={`/kategorie/${category.urlSlug}`}>{category.name}</a>
						</span>
					{/each}
				</div>
			</div>
		</div>
	</div>
</footer>
