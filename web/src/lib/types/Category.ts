export default interface Category {
	ok?: boolean;
	message?: string;
	id?: number;
	content: string;
	image: string | null;
	inMenu: boolean;
	name: string;
	urlSlug: string;
}
