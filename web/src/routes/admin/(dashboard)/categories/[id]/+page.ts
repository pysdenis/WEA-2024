import { error } from '@sveltejs/kit';
import type { PageLoad } from './$types';
import type Category from '$lib/types/Category';
import { fetchCategories } from '$lib/api/fetchFromDatabase';

let category: Category;

export const load: PageLoad = async ({ params }) => {

	const response = await fetchCategories(Number(params.id));
	category = response as Category;

	if (!category) {
		error(404, 'Not found');
	}

	return { category };
};
