import { CommonModule } from '@angular/common';
import { Component, ElementRef, Input, ViewChild, AfterViewInit, Renderer2 } from '@angular/core';

@Component({
  selector: 'app-modal',
  templateUrl: './modal.component.html',
  imports: [CommonModule],
  styleUrls: ['./modal.component.css']
})
export class ModalComponent {
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
}