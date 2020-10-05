<?php

namespace App\Http\Controllers;
use App\Models\Modulo; /* entitie model */
use App\Models\LinkModulo; /* entitie model */
use App\Models\ModuloPerfil; /* entitie model */
use App\Models\ModuloUser; /* entitie model */
use App\User; /* entitie model */
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
	
	public function getSubLinksByPerfilAndUser($id_link){
		$response = [];
		//$response = LinkModulo::withoutTrashed()->where('padre',  $id_link)->where('estado', 1)->get();
		
		$link_modulos = DB::table('link_modulos')
						  ->join('link_perfiles', 'link_modulos.id', '=', 'link_perfiles.link_id')
						  ->select('link_modulos.id', 'link_modulos.modulo', 'link_modulos.page', 
						  'link_modulos.url', 'link_modulos.estado', 'link_modulos.padre')
						  ->where('link_perfiles.perfil_id', 4)
						  ->where('link_modulos.padre', $id_link)
						  ->where('link_perfiles.estado', 1)
						  ->where('link_modulos.estado', 1);

			$response = DB::table('link_modulos')
						  ->join('link_users', 'link_modulos.id', '=', 'link_users.link_id')
						  ->select('link_modulos.id', 'link_modulos.modulo', 'link_modulos.page', 
						  'link_modulos.url', 'link_modulos.estado', 'link_modulos.padre')
						  ->where('link_users.usuario_id', 1)
						  ->where('link_modulos.padre', $id_link)
						  ->where('link_modulos.estado', 1)
						  ->where('link_users.estado', 1)
						  ->union($link_modulos)->get();
		
		return $response;
	}	
	

    public function getLinksModuloByPerfilAndUser($id_modulo){
		$response = [];
		//$response = LinkModulo::withoutTrashed()->where('modulo',  $id_modulo)->where('estado', 1)->whereNull('padre')->get();
		
		$link_modulos = DB::table('link_modulos')
						  ->join('link_perfiles', 'link_modulos.id', '=', 'link_perfiles.link_id')
						  ->select('link_modulos.id', 'link_modulos.modulo', 'link_modulos.page', 
						  'link_modulos.url', 'link_modulos.estado', 'link_modulos.padre')
						  ->where('link_perfiles.perfil_id', 4)
						  ->whereNull('link_modulos.padre')
						  ->where('link_modulos.modulo', $id_modulo)
						  ->where('link_modulos.estado', 1)
						  ->where('link_perfiles.estado', 1);
						  
		$response = DB::table('link_modulos')
						  ->join('link_users', 'link_modulos.id', '=', 'link_users.link_id')
						  ->select('link_modulos.id', 'link_modulos.modulo', 'link_modulos.page', 
						  'link_modulos.url', 'link_modulos.estado', 'link_modulos.padre')
						  ->where('link_users.usuario_id', 1)
						  ->where('link_users.estado', 1)
						  ->whereNull('link_modulos.padre')
						  ->where('link_modulos.modulo', $id_modulo)
						  ->where('link_modulos.estado', 1)
						  ->union($link_modulos)->get();						  
						  
		
		$i=0;
		foreach($response  as $link){
			$heading = false;
			$response[$i]->hijos = $this->getSubLinksByPerfilAndUser($link->id);
			$contador = count($response[$i]->hijos);
			$response[$i]->heading = $contador > 0 ? true : false; 
			$i++;
		}		
		
		return $response;		
	}	

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		/*
		   $query = ModuloUser::withoutTrashed()
					->join('modulos', 'modulo_users.modulo_id', 'modulos.id')
					->select('modulos.id', 'modulos.nombre', 'modulos.descripcion', 'modulos.estado', 'modulos.icon', 'modulos.img', 'modulos.url')
					->where('usuario_id', '=', 1)
					->where('modulos.estado', '=', 1)
					->where('modulo_users.estado', '=', 1);
		$response = ModuloPerfil::withoutTrashed()
					->join('modulos', 'modulo_perfiles.modulo_id', 'modulos.id')
					->select('modulos.id', 'modulos.nombre', 'modulos.descripcion', 'modulos.estado', 'modulos.icon', 'modulos.img', 'modulos.url')
					->where('perfil_id',  4)					
					->where('modulos.estado', '=', 1)
					->where('modulo_perfiles.estado', '=', 1)
					->unionAll($query)->get();*/

			$modulosperfil = DB::table('modulos')
								->join('modulo_perfiles', 'modulos.id', '=', 'modulo_perfiles.modulo_id')
								->select('modulos.id', 'modulos.nombre', 'modulos.descripcion', 'modulos.estado', 'modulos.icon', 'modulos.img', 'modulos.url')
								->where('modulo_perfiles.perfil_id',  4)
								->where('modulo_perfiles.estado',  1)
								->where('modulos.estado', '=', 1)
								->whereNull('modulo_perfiles.deleted_at')
								->whereNull('modulos.deleted_at');
								
			$modulos = DB::table('modulos')
								->join('modulo_users', 'modulos.id', '=', 'modulo_users.modulo_id')
								->select('modulos.id', 'modulos.nombre', 'modulos.descripcion', 'modulos.estado', 'modulos.icon', 'modulos.img', 'modulos.url')
								->where('modulo_users.usuario_id',  1)
								->where('modulo_users.estado',  1)
								->where('modulos.estado', '=', 1)
								->whereNull('modulo_users.deleted_at')
								->whereNull('modulos.deleted_at')
								->union($modulosperfil)->get();

		$i=0;
		foreach($modulos  as $modu){
			$modulos[$i]->pages_menu = $this->getLinksModuloByPerfilAndUser($modu->id);
			$contador = count($modulos[$i]->pages_menu);
			$i++;
		}		
		
		//return view('home');		
        return response()->json($modulos);
    }
}
