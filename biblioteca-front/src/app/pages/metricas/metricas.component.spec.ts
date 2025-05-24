import { ComponentFixture, TestBed } from '@angular/core/testing';
import { MetricasComponent } from './metricas.component';
import { ApiService } from '../../services/api.service';
import { of } from 'rxjs';
import { HttpClientTestingModule } from '@angular/common/http/testing';

describe('MetricasComponent', () => {
  let component: MetricasComponent;
  let fixture: ComponentFixture<MetricasComponent>;
  let mockApiService: jasmine.SpyObj<ApiService>;

  beforeEach(async () => {
    mockApiService = jasmine.createSpyObj('ApiService', ['request']);

    await TestBed.configureTestingModule({
      declarations: [MetricasComponent],
      imports: [HttpClientTestingModule],
      providers: [{ provide: ApiService, useValue: mockApiService }]
    }).compileComponents();

    fixture = TestBed.createComponent(MetricasComponent);
    component = fixture.componentInstance;
  });

  it('debería crear el componente', () => {
    expect(component).toBeTruthy();
  });

  it('debería cargar las métricas correctamente', () => {
    const mockData = {
      libro_mas_solicitado: { libro: { titulo: 'Libro X' }, total: 8 },
      ranking_libros: [],
      prestamos_activos: 5,
      total_prestamos: 12,
      total_entregados: 7
    };

    mockApiService.request.and.returnValue(Promise.resolve(mockData));

    component.ngOnInit();

    fixture.whenStable().then(() => {
      expect(component.libroMasSolicitado.libro.titulo).toBe('Libro X');
      expect(component.prestamosActivos).toBe(5);
      expect(component.total).toBe(12);
    });
  });
});