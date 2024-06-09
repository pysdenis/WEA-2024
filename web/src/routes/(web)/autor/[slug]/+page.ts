import type { PageLoad } from './$types';
import type Author from '$lib/types/Author';
import { fetchAuthors } from '../../../../lib/api/fetchFromDatabase';
import { error } from '@sveltejs/kit';

let author: Author;

export const load: PageLoad = async ({ params }) => {

	const response = await fetchAuthors(params.slug);
	author = response as Author;

	if (!author || author.firstName === null) {
		error(404, 'Not found');
	}

	return { author };
};
