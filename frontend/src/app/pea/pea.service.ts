import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PeaService {

  constructor(private _http:HttpClient) { }

  public getLista(){
    return this._http.get(`${environment.apiUrl}/pea/peeriodo_facturacion`);
  }  
}
