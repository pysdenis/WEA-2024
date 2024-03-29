const BASE_URL = 'http://localhost:8000'; // upravit a vymyslet, tak aby fungovalo lokálně i pak na serveru

export async function fetchData(endpoint: string): Promise<Record<string, article>>{
	const response = await fetch(`${BASE_URL}`); // dopsat '/api/${endpoint}' jak je níže
	return await response.json();
}

export async function postData(endpoint: string, data: Record<string, unknown>): Promise<Record<string, article>>{
	const response = await fetch(`${BASE_URL}/api/${endpoint}`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	});
	return await response.json();
}

interface article {
	id: number;
	title: string;
	content: string;
}
