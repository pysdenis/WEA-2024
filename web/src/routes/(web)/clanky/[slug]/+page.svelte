<script lang="ts">
	import type Article from '$lib/types/Article';

	export let data: { article: Article };

	let category: Article = data.article;
</script>

<pre>
	{JSON.stringify(category, null, 2)}
</pre>
