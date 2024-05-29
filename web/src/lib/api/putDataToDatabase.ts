import type { loginData } from "../types/Admin";
import type Admin from "../types/Admin";
import type Article from "../types/Article";
import type Author from "../types/Author";
import type Category from "../types/Category";
import { putData } from "./api";

export async function putArticle(data: Article): Promise<Article> {
	const endpoint: string = 'articles';
	return putData<Article>(endpoint, data);
}

export async function putCategory(data: Category): Promise<Category> {
	const endpoint: string = 'categories';
	return putData<Category>(endpoint, data);
}

export async function putAuthor(data: Author): Promise<Author> {
	const endpoint: string = 'authors';
	return putData<Author>(endpoint, data);
}

export async function putAdminData(data: Admin): Promise<Admin> {
	const endpoint: string = 'users_admin';
	return putData<Admin>(endpoint, data);
}

export async function putLoginData(data: loginData): Promise<loginData> {
	const endpoint: string = 'login';
	return putData<loginData>(endpoint, data);
}
