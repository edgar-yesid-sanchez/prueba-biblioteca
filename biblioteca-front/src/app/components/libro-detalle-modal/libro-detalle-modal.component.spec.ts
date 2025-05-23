import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LibroDetalleModalComponent } from './libro-detalle-modal.component';

describe('LibroDetalleModalComponent', () => {
  let component: LibroDetalleModalComponent;
  let fixture: ComponentFixture<LibroDetalleModalComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LibroDetalleModalComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(LibroDetalleModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
