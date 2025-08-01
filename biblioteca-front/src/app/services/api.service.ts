import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private baseUrl = 'http://localhost:8000/api';

  private getToken(): string | null {
    return localStorage.getItem('token');
  }

  request(endpoint: string, method: string = 'GET', body?: any): Promise<any> {
    const headers: any = { 'Content-Type': 'application/json' };
    const token = this.getToken();
    if (token) headers['Authorization'] = `Bearer ${token}`;

    return fetch(`${this.baseUrl}/${endpoint}`, {
      method,
      headers,
      body: body ? JSON.stringify(body) : undefined
    }).then(async res => {
      if (res.status === 204) return null; // requests return 204 No Content

      const data = await res.json();
      if (!res.ok) throw data;
      return data;
    }).catch(err => {
      console.error('Error in API request:', err);
      throw err;
    });
  }
}
