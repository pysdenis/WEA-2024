export default interface Article {
	id: number;
	title: string;
	createdAt: string;
	publishedAt: string;
	categoryId: number;
	authorId: number;
	image: string;
	content: string;
	perex: string;
	urlSlug: string;
}
