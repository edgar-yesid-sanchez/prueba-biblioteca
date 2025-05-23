import { CommonModule } from '@angular/common';
import { Component, Input, Output, EventEmitter, HostListener, ViewChild, ElementRef, Renderer2 } from '@angular/core';

@Component({
  selector: 'app-libro-detalle-modal',
  imports: [CommonModule],
  templateUrl: './libro-detalle-modal.component.html'
})
export class LibroDetalleModalComponent {
 @Input() libro: any = null;
  @Input() prestamo: any = null;
  @ViewChild('modalRef') modalRef!: ElementRef;

  visible = false;
  top = 0;

  constructor(private renderer: Renderer2) {}

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
}
