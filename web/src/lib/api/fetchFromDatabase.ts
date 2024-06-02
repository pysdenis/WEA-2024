import type Admin from "../types/Admin";
import type Article from "../types/Article";
import type Author from "../types/Author";
import type Category from "../types/Category";
import { fetchData } from "./api";

export async function fetchArticles(id?: number | string): Promise< Article | Article[]> {
	const endpoint: string = 'articles';
	return fetchData<Article | Article[]>(endpoint, id);
}

export async function fetchCategories(id?: number | string): Promise<Category | Category[]> {
	const endpoint: string = 'categories';
	return fetchData<Category | Category[]>(endpoint, id);
}

export async function fetchAuthors(id?: number | string): Promise<Author | Author[]> {
	const endpoint: string = 'authors';
	return fetchData<Author | Author[]>(endpoint, id);
}

export async function fetchAdminData(id?: number): Promise<Admin | Admin[]> {
	const endpoint: string = 'users_admin';
	return fetchData<Admin | Admin[]>(endpoint, id);
}
