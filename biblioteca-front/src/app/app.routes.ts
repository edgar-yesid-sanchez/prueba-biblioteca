import { Routes } from '@angular/router';
import { AuthGuard } from './services/auth.guard';
import { RoleGuard } from './services/role.guard';

export const routes: Routes = [

  { path: '', loadComponent:() => import('./pages/library/library.component').then(m => m.LibraryComponent), },
  { path: 'prestamos',
    canActivate: [
      AuthGuard
    ],
    loadComponent:() => import('./pages/prestamos/prestamos.component').then(m => m.PrestamosComponent)
  },
  { path: 'gestionarLibros',
    canActivate: [RoleGuard(['admin'])],
    loadComponent:() => import('./pages/gestionar-libros/gestionar-libros.component').then(m => m.GestionarLibrosComponent)
  },


];
