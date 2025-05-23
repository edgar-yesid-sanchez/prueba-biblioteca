import { Component, OnInit, ViewChild } from '@angular/core';
// import { ModalComponent } from '../modal/modal.component';
import { CommonModule } from '@angular/common';
import { LibrosService } from '../../services/libros.service';
import { ApiService } from '../../services/api.service';
import { LibroDetalleModalComponent } from '../libro-detalle-modal/libro-detalle-modal.component';

@Component({
  selector: 'app-library',
  imports: [LibroDetalleModalComponent,CommonModule],
  templateUrl: './library.component.html',
  styleUrl: './library.component.css'
})
export class LibraryComponent implements OnInit {
  @ViewChild(LibroDetalleModalComponent) stickyModal!: LibroDetalleModalComponent;

  libros: any[] = [];
  estanterias: any[] = [];
  cargando: boolean = true;
  mostrarModalDetalleDeLibro: boolean = false;

  libroSeleccionado: any = null;
  ultimoPrestamoLibroSeleccionado: any = null;

  constructor(
    private librosService: LibrosService,
    private api: ApiService
  ) {}


  
  ngOnInit(): void {
    this.librosService.getAll()
      .then(async (data) => {
        this.libros = data;
        this.cargando = false;
      })
      .catch(error => {
        console.error('Error cargando libros', error);
        this.cargando = false;
      });
  }

  dividirEnEstanterias(array: any[], size: number): any[][] {
    const result = [];
    for (let i = 0; i < array.length; i += size) {
      result.push(array.slice(i, i + size));
    }
    return result;
  }

  seleccionarLibro(libro: any, event: MouseEvent) {
 const target = event.currentTarget as HTMLElement;
  this.libroSeleccionado = libro;

  if (!libro.disponible) {
    this.api.request('prestamos').then(prestamos => {
      const ult = prestamos
        .filter((p: any) => p.libro_id === libro.id)
        .sort((a:any, b: any) => new Date(b.fecha_prestamo).getTime() - new Date(a.fecha_prestamo).getTime())[0];

      this.ultimoPrestamoLibroSeleccionado = ult;
      this.stickyModal.libro = libro;
      this.stickyModal.prestamo = ult;
      this.stickyModal.abrirDebajoDe(target);
    });
  } else {
    this.stickyModal.libro = libro;
    this.stickyModal.prestamo = null;
    this.stickyModal.abrirDebajoDe(target);
  }
  }

  cerrarModal() {
    this.libroSeleccionado = null;
  }
}
