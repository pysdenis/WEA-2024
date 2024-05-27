import { writable } from 'svelte/store';
import type { User } from '$lib/types/Admin';

export const adminStore = writable<User>();

export function loadUserFromLocalStorage() {
	if (typeof localStorage !== 'undefined') {
		const storedUser = localStorage.getItem('token');
		if (storedUser) {
			const user: User = JSON.parse(storedUser);
			adminStore.set(user);
		}
	}
}
