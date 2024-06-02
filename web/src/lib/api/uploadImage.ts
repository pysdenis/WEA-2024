import { BASE_URL } from "./api";

export async function uploadImage(formData: FormData): Promise<string | null> {
	try {
		const response = await fetch(`${BASE_URL}/upload-image`, {
			method: 'POST',
			body: formData
		});
		const data = await response.json();
		return data.url;
	} catch (error) {
		console.error('Error uploading image:', error);
		return null;
	}
}
