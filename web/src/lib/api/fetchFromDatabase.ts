import type Article from "../types/Article";
import type Author from "../types/Author";
import type Category from "../types/Category";
import { fetchData } from "./api";

export async function fetchArticles(endpoint: string, id?: number): Promise<Article[]> {
	return fetchData<Article[]>(endpoint, id);
}

export async function fetchCategories(endpoint: string, id?: number): Promise<Category[]> {
	return fetchData<Category[]>(endpoint, id);
}

export async function fetchAuthors(endpoint: string, id?: number): Promise<Author[]> {
	return fetchData<Author[]>(endpoint, id);
}
