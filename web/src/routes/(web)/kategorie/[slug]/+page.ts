import { error } from '@sveltejs/kit';
import type { PageLoad } from './$types';
import { fetchCategories } from '$lib/api/fetchFromDatabase';
import type Category from '$lib/types/Category';

let category: Category;

export const load: PageLoad = async ({ params }) => {

	const response = await fetchCategories(params.slug);
	category = response as Category;

	if (!category || category.name === null) {
		error(404, 'Not found');
	}

	return { category };
};
