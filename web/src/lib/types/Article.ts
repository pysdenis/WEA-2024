export default interface Article {
	ok?: boolean;
	message?: string;
	id?: number;
	title: string;
	createdAt: string;
	publishedAt: string;
	categoryId: number;
	authorId: number;
	image: string | null;
	content: string;
	perex: string;
	urlSlug: string;
}
