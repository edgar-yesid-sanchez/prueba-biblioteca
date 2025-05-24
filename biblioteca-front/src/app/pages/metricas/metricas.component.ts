import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-metricas',
  imports: [CommonModule],
  templateUrl: './metricas.component.html',
  styleUrl: './metricas.component.css'
})
export class MetricasComponent implements OnInit {
  cargando = true;
  libroMasSolicitado: any = null;
  ranking: any[] = [];
  prestamosActivos = 0;
  entregados = 0;
  total = 0;

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.cargarMetricas();
  }
  cargarMetricas(){
    this.api.request('metricas').then((res: any) => {
      console.log(res);
      this.libroMasSolicitado = res.libro_mas_solicitado;
      this.ranking = res.ranking_libros;
      this.prestamosActivos = res.prestamos_activos;
      this.entregados = res.total_entregados;
      this.total = res.total_prestamos;
    }).finally(() => {
      this.cargando = false;
    })
  }
}
