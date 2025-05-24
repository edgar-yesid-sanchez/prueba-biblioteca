import { CommonModule } from '@angular/common';
import { Component, Input, Output, EventEmitter, HostListener, ViewChild, ElementRef, Renderer2 } from '@angular/core';
import { PrestamosService } from '../../services/prestamos.service';
import { AuthService } from '../../services/auth.service';
import moment from 'moment';

@Component({
  selector: 'app-libro-detalle-modal',
  imports: [CommonModule],
  templateUrl: './libro-detalle-modal.component.html'
})
export class LibroDetalleModalComponent {
  @Input() libro: any = null;
  @Input() prestamo: any = null;
  @Output() prestamoCreado = new EventEmitter<void>();
  @ViewChild('modalRef') modalRef!: ElementRef;

  visible = false;
  top = 0;

  constructor(
    private renderer: Renderer2,
    private prestamoService: PrestamosService,
    private authService: AuthService
  ) {}

  ngAfterViewInit(): void {
    // Inicialmente oculto
    this.renderer.setStyle(this.modalRef.nativeElement, 'display', 'none');
  }

  abrirDebajoDe(target: HTMLElement) {
    const rect = target.getBoundingClientRect();
    this.top = rect.bottom + window.scrollY + 8; // espacio debajo
    this.visible = true;
    this.renderer.setStyle(this.modalRef.nativeElement, 'display', 'block');
  }

  cerrar() {
    this.visible = false;
    this.renderer.setStyle(this.modalRef.nativeElement, 'display', 'none');
  }

  // Detectar Escape
  @HostListener('document:keydown.escape', ['$event'])
  onEsc(event: KeyboardEvent) {
    this.cerrar();
  }

  // Detectar clics fuera del contenido
  onBackdropClick(event: MouseEvent) {
    if ((event.target as HTMLElement).id === 'modal-backdrop') {
      this.cerrar();
    }
  }


  /* Solicitar prestamo */
  crearPrestamo = false;


  fechaPrestamo = moment().format('YYYY-MM-DD');
  fechaDevolucion = moment().add(7, 'days').format('YYYY-MM-DD');


  mostrarSolicitud() {
    this.crearPrestamo = true;

  }

  confirmarPrestamo() {
    if (!this.authService.isAuthenticated()) {
      alert('Inicia secion para poder solicitar un préstamo');
      return;
    }
    
    this.prestamoService.create({
      libro_id: this.libro.id,
      fecha_prestamo: this.fechaPrestamo,
      fecha_devolucion: this.fechaDevolucion,
    }).then(() => {
      alert('Préstamo solicitado con éxito');
      this.crearPrestamo = false;
      this.cerrar();
      this.prestamoCreado.emit();
    }).catch((error) => {
      console.error('Error al solicitar el préstamo', error);
    });
  }
}
