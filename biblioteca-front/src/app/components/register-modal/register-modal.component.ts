import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-register-modal',
  imports: [ReactiveFormsModule , CommonModule],
  templateUrl: './register-modal.component.html',
  styleUrl: './register-modal.component.css'
})
export class RegisterModalComponent implements OnInit {
  @Input() visible = false;
  @Output() onClose = new EventEmitter<void>();
  @Output() onLogin = new EventEmitter<any>();

  form!: FormGroup;
  error = '';

  constructor(private fb: FormBuilder, private auth: AuthService) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
    });
  }

  register() {
    if (this.form.invalid) return;

    const { name, email, password } = this.form.value;

    this.auth.register(name, email, password)
      .then(user => {
        this.onLogin.emit(user);
        this.onClose.emit();
      })
      .catch(err => this.error = err?.error || 'Error al registrar');
  }

  cerrar() {
    this.onClose.emit();
  }
}