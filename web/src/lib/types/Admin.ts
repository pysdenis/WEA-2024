export default interface Admin {
	id?: number;
	email: string;
	loginName: string;
	loginPassword: string;
}

export interface loginData {
	token?: string;
	email: string;
	loginPassword: string;
}

export interface User {
	id: number;
	email: string;
	loginName: string;
}
