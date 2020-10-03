import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PeaRoutingModule } from './pea-routing.module';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '../shared/shared.module';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { IndexComponent } from './components/index/index.component';
import { CrearOdsComponent } from './components/crear-ods/crear-ods.component';



@NgModule({
  declarations: [
    IndexComponent,
    CrearOdsComponent
  ],
  imports: [
    CommonModule,
    PeaRoutingModule,
    NgbModule,
    SharedModule,
    FormsModule,
    ReactiveFormsModule,
  ]
})
export class PeaModule { }
