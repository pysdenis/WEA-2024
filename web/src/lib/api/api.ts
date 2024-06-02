export const BASE_URL = 'http://localhost:8000';

export async function fetchData<T>(endpoint: string, arg?: unknown): Promise<T> {
	let url = `${BASE_URL}/${endpoint}`;
	if (arg) {
		url += `/${arg}`;
	}

	const response = await fetch(url, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json'
		}
	});

	const data: T = await response.json();
	return data;
}

export async function postData<T>(endpoint: string, data: T): Promise<T> {
	const response = await fetch(`${BASE_URL}/${endpoint}`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	});

	const responseData: T = await response.json();
	return responseData;
}

export async function putData<T>(endpoint: string, data: T): Promise<T> {
	const response = await fetch(`${BASE_URL}/${endpoint}`, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	});

	const responseData: T = await response.json();
	return responseData;
}

export async function deleteData<T>(endpoint: string, arg: unknown): Promise<T> {
	const response = await fetch(`${BASE_URL}/${endpoint}/${arg}`, {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/json'
		}
	});

	const responseData: T = await response.json();
	return responseData;
}
