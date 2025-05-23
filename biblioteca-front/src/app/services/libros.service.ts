import { Injectable } from '@angular/core';
import { ApiService } from './api.service';

@Injectable({ providedIn: 'root' })
export class LibrosService {
  constructor(private api: ApiService) {}

  getAll() {
    return this.api.request('libros');
  }

  create(libro: any) {
    return this.api.request('libros', 'POST', libro);
  }

  update(id: number, libro: any) {
    return this.api.request(`libros/${id}`, 'PUT', libro);
  }

  delete(id: number) {
    return this.api.request(`libros/${id}`, 'DELETE');
  }
}
