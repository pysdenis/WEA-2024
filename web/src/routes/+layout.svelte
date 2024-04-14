<script>
	import "$lib/assets/css/main.css";
	import logo from "$lib/assets/images/logo.png";

	let pageTitle = "Random Page";
	let pageContent = "This is a test page.";
</script>

<main class="container mx-auto">
	<h1 class="text-4xl font-bold bg-red-500">{pageTitle}</h1>
	<p class="text-lg">{pageContent}</p>
	TODO: Svelte routing
	<img src={logo} alt="Logo" />
	<a href="/test">test</a>
	<slot></slot>
</main>

