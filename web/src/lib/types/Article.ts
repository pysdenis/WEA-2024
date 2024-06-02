export default interface Article {
	ok?: boolean;
	message?: string;
	id?: number;
	title: string;
	createdAt: string;
	publishedAt: string;
	categoryName?: string;
	categoryId?: number;
	authorId?: number;
	authorName?: string;
	authorUrlSlug?: string;
	image: string | null;
	content: string;
	perex: string;
	urlSlug: string;
}
