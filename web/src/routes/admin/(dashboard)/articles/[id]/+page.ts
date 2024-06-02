import { error } from '@sveltejs/kit';
import type { PageLoad } from './$types';
import type Article from '$lib/types/Article';
import { fetchArticles } from '$lib/api/fetchFromDatabase';

let article: Article;

export const load: PageLoad = async ({ params }) => {

	const response = await fetchArticles(Number(params.id));
	article = response as Article;

	if (!article) {
		error(404, 'Not found');
	}

	return { article };
};
