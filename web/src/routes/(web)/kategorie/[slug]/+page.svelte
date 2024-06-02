<script lang="ts">
	import type Category from "$lib/types/Category";

	export let data: { category: Category };

	let category: Category = data.category;

	console.log(category);
</script>

<pre>{JSON.stringify(category, null, 2)}</pre>
