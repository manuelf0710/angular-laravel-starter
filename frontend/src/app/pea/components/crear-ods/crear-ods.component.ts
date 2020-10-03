import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { environment } from './../../../../environments/environment';
import { PeaService } from './../../pea.service';

@Component({
  selector: 'app-crear-ods',
  templateUrl: './crear-ods.component.html',
  styleUrls: ['./crear-ods.component.css']
})
export class CrearOdsComponent implements OnInit {
  
  public loading = false;
  public url = environment.apiUrl+'/comun/buscarproducto';
  formulario: FormGroup;

  constructor(
    private FormBuilder: FormBuilder,
    public _PeaService : PeaService    
  ) { 
    
  }

  ngOnInit(): void {
    this.buildForm();
  }

  buildForm(){
    let id = null
    let fecha_inicio = null
    let fecha_fin = null

    this.formulario = this.FormBuilder.group({
      id:[id],
      fecha_inicio:[fecha_inicio, [Validators.required]],
      fecha_fin: [fecha_fin, [Validators.required]],
    });  
  }  

  seleccionado(item){
    console.log("selcionado ",item);
  }
  cargarFacturacion(){
    alert();
  }

  guardar(event: Event){
    event.preventDefault();
    if (this.formulario.valid) {
      alert("es valido")
    }else{
      this.formulario.markAllAsTouched();
    }
  }

}
