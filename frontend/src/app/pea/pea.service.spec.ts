import { TestBed } from '@angular/core/testing';

import { PeaService } from './pea.service';

describe('PeaService', () => {
  let service: PeaService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PeaService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
