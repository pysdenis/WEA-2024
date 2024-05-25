const BASE_URL = 'http://localhost:8000'; // upravit a vymyslet, tak aby fungovalo lokálně i pak na serveru

export async function fetchData<T>(endpoint: string, id?: number): Promise<T> {
	let url = `${BASE_URL}/${endpoint}`;
	if (id) {
		url += `/${id}`;
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

// export async function postData(
// 	endpoint: string,
// 	data: Record<string, unknown>
// ): Promise<Record<string, unknown>> {
// 	const response = await fetch(`${BASE_URL}/api/${endpoint}`, {
// 		method: 'POST',
// 		headers: {
// 			'Content-Type': 'application/json'
// 		},
// 		body: JSON.stringify(data)
// 	});
// 	return await response.json();
// }
