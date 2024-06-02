export default interface Author {
	ok?: boolean;
	message?: string;
	id?: number;
	firstName: string;
	lastName: string;
	email: string;
	phoneNumber: string;
	content: string;
	image: string | null;
	urlSlug: string;
}
