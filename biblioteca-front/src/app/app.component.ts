import { Component } from '@angular/core';
// import { RouterOutlet } from '@angular/router';
import { LibraryComponent } from './components/library/library.component';
import { HeaderComponent } from './components/header/header.component';

@Component({
  selector: 'app-root',
  imports: [HeaderComponent,LibraryComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'biblioteca-front';
}
