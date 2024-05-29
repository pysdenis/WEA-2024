import type Admin from "../types/Admin";
import type Article from "../types/Article";
import type Author from "../types/Author";
import type Category from "../types/Category";
import { deleteData } from "./api";

export async function deleteArticle(id: number): Promise<Article> {
	const endpoint: string = 'articles';
	return deleteData<Article>(endpoint, id);
}

export async function deleteCategory(id: number): Promise<Category> {
	const endpoint: string = 'categories';
	return deleteData<Category>(endpoint, id);
}

export async function deleteAuthor(id: number): Promise<Author> {
	const endpoint: string = 'authors';
	return deleteData<Author>(endpoint, id);
}

export async function deleteAdminData(id: number): Promise<Admin> {
	const endpoint: string = 'users_admin';
	return deleteData<Admin>(endpoint, id);
}
