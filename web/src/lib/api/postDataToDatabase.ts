import type { loginData } from "../types/Admin";
import type Admin from "../types/Admin";
import type Article from "../types/Article";
import type Author from "../types/Author";
import type Category from "../types/Category";
import { postData } from "./api";

export async function postArticle(data: Article): Promise<Article> {
	const endpoint: string = 'articles';
	return postData<Article>(endpoint, data);
}

export async function postCategory(data: Category): Promise<Category> {
	const endpoint: string = 'categories';
	return postData<Category>(endpoint, data);
}

export async function postAuthor(data: Author): Promise<Author> {
	const endpoint: string = 'authors';
	return postData<Author>(endpoint, data);
}

export async function postAdminData(data: Admin): Promise<Admin> {
	const endpoint: string = 'users_admin';
	return postData<Admin>(endpoint, data);
}

export async function postLoginData(data: loginData): Promise<loginData> {
	const endpoint: string = 'login';
	return postData<loginData>(endpoint, data);
}

export async function postRegisterData(data: Admin): Promise<Admin> {
	const endpoint: string = 'register';
	return postData<Admin>(endpoint, data);
}
