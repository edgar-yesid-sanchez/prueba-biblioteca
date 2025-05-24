import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { CommonModule } from '@angular/common';
import moment from 'moment';


@Component({
  selector: 'app-prestamos',
  imports: [CommonModule],
  templateUrl: './prestamos.component.html'
})
export class PrestamosComponent implements OnInit {
  prestamos: any[] = [];

  constructor(private api: ApiService) {}

  user: any;

  ngOnInit(): void {
    this.api.request('me').then(res => {
      this.user = res.user;
    })
    this.cargarPrestamos();
  }

  cargarPrestamos() {
    this.api.request('prestamos').then(data => {
      console.log('prestamos', data);
      this.prestamos = data.sort((a: any, b: any) => {
        return a.estado === 'pendiente' && b.estado !== 'pendiente' ? -1 :
              b.estado === 'pendiente' && a.estado !== 'pendiente' ? 1 : 0;
      });
    }).catch(err => {
      console.error('Error cargando prestamos', err);
    });
  }

  marcarComoEntregado(id: number) {
    this.api.request(`prestamos/${id}`, 'PUT', {
      estado: 'entregado'
    }).then(() => {
      alert('Préstamo actualizado');
      this.cargarPrestamos();
    }).catch(err => {
      console.error('Error actualizando préstamo', err);
    });
  }

  renovarPrestamo(prestamo: any) {
    this.api.request(`prestamos/${prestamo.id}`, 'PUT', {
      fecha_devolucion: moment(prestamo.fecha_devolucion).add(7, 'days').format('YYYY-MM-DD')
    }).then(() => {
      alert('Préstamo actualizado');
      this.cargarPrestamos();
    }).catch(err => {
      console.error('Error actualizando préstamo', err);
    });
  }
  
}
