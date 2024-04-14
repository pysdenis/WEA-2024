<script>
	import "$lib/assets/css/main.css";
	import logo from "$lib/assets/images/logo.png";

	let pageTitle = "Random Page layout";
</script>

<main class="container mx-auto">
	<h1 class="text-4xl font-bold bg-red-500">{pageTitle}</h1>
	//TODO: https://kit.svelte.dev/docs/routing
	<img src={logo} alt="Logo" />
	<slot></slot>
</main>

