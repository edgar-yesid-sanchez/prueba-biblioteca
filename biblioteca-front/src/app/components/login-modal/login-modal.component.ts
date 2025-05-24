import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login-modal',
  imports: [ReactiveFormsModule ,CommonModule],
  templateUrl: './login-modal.component.html',
  styleUrl: './login-modal.component.css'
})
export class LoginModalComponent implements OnInit {
  @Input() visible = false;
  @Output() onClose = new EventEmitter<void>();
  @Output() onLogin = new EventEmitter<any>();

  form!: FormGroup;
  error = '';

  constructor(private fb: FormBuilder, private auth: AuthService) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
    });
  }

  login() {
    if (this.form.invalid) return;

    const { email, password } = this.form.value;

    this.auth.login(email, password)
      .then(user => {
        this.onLogin.emit(user);
        this.onClose.emit();
      })
      .catch(err => this.error = err?.error || 'Credenciales inválidas');
  }

  cerrar() {
    this.onClose.emit();
  }
}