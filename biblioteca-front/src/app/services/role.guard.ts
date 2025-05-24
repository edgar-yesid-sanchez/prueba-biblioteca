import { CanActivateFn } from '@angular/router';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from './auth.service';


export const RoleGuard = (allowedRoles: string[]): CanActivateFn => {
  return async () => {
    const authService = inject(AuthService);
    const router = inject(Router);
    const token = authService.getToken();

    if (!token) {
      router.navigate(['/']);
      return false;
    }

    const payload = await authService.me().then(res => {
      return res.user;
    })
    console.log('Payload:', payload);
    const userRole = payload?.role;
    console.log('User role:', userRole);
    if (!allowedRoles.includes(userRole)) {
      alert('No tienes permisos para acceder a esta sección');
      return false;
    }

    return true;
  };
};
