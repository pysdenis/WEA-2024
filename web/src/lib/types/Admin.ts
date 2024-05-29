export default interface Admin {
	token?: string;
	message?: string;
	id?: number;
	email: string;
	loginName: string;
	loginPassword: string;
}

export interface loginData {
	token?: string;
	message?: string;
	email: string;
	loginPassword: string;
}

export interface User {
	email: string;
	loginName: string;
}
