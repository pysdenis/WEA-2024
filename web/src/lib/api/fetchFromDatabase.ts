import type Admin from "../types/Admin";
import type Article from "../types/Article";
import type Author from "../types/Author";
import type Category from "../types/Category";
import { fetchData } from "./api";

export async function fetchArticles(id?: number): Promise<Article[]> {
	const endpoint: string = 'articles';
	return fetchData<Article[]>(endpoint, id);
}

export async function fetchCategories(id?: number): Promise<Category[]> {
	const endpoint: string = 'categories';
	return fetchData<Category[]>(endpoint, id);
}

export async function fetchAuthors(id?: number): Promise<Author[]> {
	const endpoint: string = 'authors';
	return fetchData<Author[]>(endpoint, id);
}

export async function fetchAdminData(id?: number): Promise<Admin[]> {
	const endpoint: string = 'users_admin';
	return fetchData<Admin[]>(endpoint, id);
}
