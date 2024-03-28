const BASE_URL = 'http://localhost:8080/backend'; // upravit a vymyslet, tak aby fungovalo lokálně i pak na serveru

export async function fetchData(endpoint: string): Promise<Record<string, unknown>>{
	const response = await fetch(`${BASE_URL}/api/${endpoint}`);
	return await response.json();
}

export async function postData(endpoint: string, data: Record<string, unknown>): Promise<Record<string, unknown>>{
	const response = await fetch(`${BASE_URL}/api/${endpoint}`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	});
	return await response.json();
}
