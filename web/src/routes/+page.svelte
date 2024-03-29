<script>
	import { onMount } from "svelte";
	import "../app.css";
	import { fetchData } from '../lib/api';

	let articles = []; // Inicializujeme pole pro uložení načtených článků

	onMount(async () => {
		const response = await fetchData('data.php');
		// @ts-ignore
		articles = response; // Uložíme načtená data do proměnné articles
	});
</script>

<h1 class="bg-slate-700 text-white">Welcome to SvelteKit</h1>
<p>Visit <a href="https://kit.svelte.dev" class="leading-5 text-red-500">kit.svelte.dev</a> to read the documentation</p>
{#each articles as article}
	<li>
		<h2>{article.title}</h2>
		<p>{article.content}</p>
	</li>
{/each}
