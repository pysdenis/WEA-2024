import { error } from '@sveltejs/kit';
import type { PageLoad } from './$types';
import { fetchAuthors } from '$lib/api/fetchFromDatabase';
import type Author from '$lib/types/Author';

let author: Author;

export const load: PageLoad = async ({ params }) => {

	const response = await fetchAuthors(Number(params.id));
	author = response as Author;

	if (!author) {
		error(404, 'Not found');
	}

	return { author };
};
