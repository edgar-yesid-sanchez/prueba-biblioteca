import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';

@Component({
  selector: 'app-gestionar-libros',
  imports: [CommonModule,FormsModule,ReactiveFormsModule ], 
  templateUrl: './gestionar-libros.component.html'
})
export class GestionarLibrosComponent implements OnInit {
  libros: any[] = [];
  modoEdicion: { [id: number]: boolean } = {};
  formLibro!: FormGroup;
  verFormAgregarLibro: boolean = false;
  constructor(private api: ApiService, private fb: FormBuilder) {}

  ngOnInit(): void {
    this.cargarLibros();

    this.formLibro = this.fb.group({
      titulo: ['', Validators.required],
      autor: ['', Validators.required],
      genero: ['', Validators.required],
      disponible: [true]
    });
  }

  cargarLibros() {
    this.api.request('libros').then(data => {
      this.libros = data;
    });
  }

  guardar(libro: any) {
    if(libro.titulo === '' || libro.autor === '' || libro.genero === '') {
      alert('Por favor completa todos los campos');
      return;
    }else if (libro.disponible === undefined) {
      libro.disponible = true;
    }
    
    this.api.request(`libros/${libro.id}`, 'PUT', libro).then(() => {
      this.modoEdicion[libro.id] = false;
      this.cargarLibros();
    });
  }

  eliminar(id: number) {
    if (confirm('¿Seguro que deseas eliminar este libro?')) {
      this.api.request(`libros/${id}`, 'DELETE').then((res) => {
        console.log(res);
        this.cargarLibros()
      })
    }
  }

  crear() {
    if (this.formLibro.invalid) return;

    this.api.request('libros', 'POST', this.formLibro.value).then(() => {
      this.formLibro.reset({ disponible: true });
      this.cargarLibros();
    });
  }
}
