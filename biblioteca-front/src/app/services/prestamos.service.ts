import { Injectable } from '@angular/core';
import { ApiService } from './api.service';

@Injectable({
  providedIn: 'root'
})
export class PrestamosService {

  constructor(private api: ApiService) {}
  getAll() {
    return this.api.request('prestamos');
  }

  create(prestamo: any) {
    return this.api.request('prestamos', 'POST', prestamo);
  }

  update(id: number, prestamo: any) {
    return this.api.request(`prestamos/${id}`, 'PUT', prestamo);
  }

  delete(id: number) {
    return this.api.request(`prestamos/${id}`, 'DELETE');
  }
}
