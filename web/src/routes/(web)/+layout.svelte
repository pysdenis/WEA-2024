<script lang="ts">
	import '$lib/assets/css/main.css';
	import logo from '$lib/assets/images/logo.png';

	let pageTitle = 'Random Page layout';
</script>

<header class="flex items-center gap-3 justify-center py-3 bg-slate-500">
	<img src={logo} alt="Logo" class="h-11" />
	<nav class="text-white">
		<a href="/">Home</a>
		<a href="/about">About</a>
		<a href="/admin">ADMIN</a>
	</nav>
</header>
<main class="container">
	<slot />
</main>
