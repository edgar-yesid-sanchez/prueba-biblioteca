import { Injectable } from '@angular/core';
import { ApiService } from './api.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private api: ApiService) {}

  login(email: string, password: string) {
    return fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    }).then(async res => {
      const data = await res.json();
      if (res.ok && data.token) {
        localStorage.setItem('token', data.token);
      }
      if (!res.ok) throw data;
      return data;
    });
  }

  register(name: string, email: string, password: string) {
    return this.api.request('register', 'POST', { name, email, password });
  }

  logout() {
    localStorage.removeItem('token');
  }

  me() {
    return this.api.request('me');
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem('token');
  }
}
