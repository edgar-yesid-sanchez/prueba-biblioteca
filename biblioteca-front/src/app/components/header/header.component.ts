import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { LoginModalComponent } from '../login-modal/login-modal.component';
import { RegisterModalComponent } from '../register-modal/register-modal.component';


@Component({
  selector: 'app-header',
  imports: [CommonModule,
    LoginModalComponent,
    RegisterModalComponent,
  ],
  templateUrl: './header.component.html',
  styleUrl: './header.component.css'
})
export class HeaderComponent implements OnInit {
  showLogin = false;
  showRegister = false;
  user: any = null;

  constructor(private auth: AuthService) {}

  ngOnInit() {
    if (this.auth.isAuthenticated()) {
      this.getUser();
    }
  }

  getUser() {
    this.auth.me().then(res =>{
      this.user = res.user;
    }).catch(err => console.error('Error fetching user data:', err));
  }

  logout() {
    this.auth.logout();
    location.reload();
  }

  handleLogin(user: any) {
    this.getUser();
    this.showLogin = false;
  }

  handleRegister(user: any) {
    this.getUser
    this.showRegister = false;
  }


}
