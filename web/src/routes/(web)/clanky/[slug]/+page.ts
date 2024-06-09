import { error } from '@sveltejs/kit';
import type { PageLoad } from './$types';
import { fetchArticles } from '$lib/api/fetchFromDatabase';
import type Article from '$lib/types/Article';

let article: Article;

export const load: PageLoad = async ({ params }) => {

	const response = await fetchArticles(params.slug);
	article = response as Article;

	if (!article || (new Date(article.publishedAt) > new Date()) || article.title === null) {
		error(404, 'Not found');
	}

	return { article };
};
