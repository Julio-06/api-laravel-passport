<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ApiTrait {

    //RELACIONES PERMITIDAS 
    protected $allowIncluded = ['posts', 'posts.user'];
    protected $allowFilter = ['id', 'name', 'slug'];
    protected $allowSort = ['id', 'name', 'slug'];

    public function scopeIncluded(Builder $query){
        //VALIDA SI LA VARIABLE included SE ENCUENTRA DEFINIDA Y LA PROPIEDAD allowIncluded
        if(empty($this->allowIncluded) || empty(request('included'))){
            return;
        }
        //METE EN UN ARRAY TODOS LOS DATOS ENVIADOS POR LA URL EJEMPLO included=post,relacion2
        $relations = explode(',', request('included')); //['posts', 'relacion2']

        //GUARDAMOS LAS RELACIONES EN UNA COLLECTION PARA UTILIZAR LOS METODOS DE LA MISMA
        $allowIncluded = collect($this->allowIncluded);

        //ITERAMOS TODAS LAS RELACIONES ENCONTRADAS
        foreach($relations as $key => $relationship){
            //VALIDARA SI EL VALOR NO EXISTE DENTRO DE LAS RELACIONES PERMITIDAS
            if(!$allowIncluded->contains($relationship)){
                //ELIMINARA LA RELACION QUE NO EXISTE DENTRO DE LAS PERMITIDAS
                unset($relations[$key]);
            }
        }

        $query->with($relations);
    }

    public function scopeFilter(Builder $query){
        //VALIDA SI LA VARIABLE filter SE ENCUENTRA DEFINIDA Y LA PROPIEDAD allowFilter
        if(empty($this->allowFilter) || empty(request('filter'))){
            return;
        }
        //RECUPERA LA VARIABLE FILTER
        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach($filters as $filter => $value){
            //VALIDA LOS FILTROS PERMITIDOS
            if($allowFilter->contains($filter)){
                //BUSCA REGISTROS QUE COINCIDA CON ALGUNA PARTE DEL TEXTO INDICADO
                $query->where($filter, 'LIKE' ,'%' . $value . '%');
            }
        }
    }

    public function scopeSort(Builder $query){
        //VALIDA SI LA VARIABLE sort SE ENCUENTRA DEFINIDA Y LA PROPIEDAD allowSort
        if(empty($this->allowSort) || empty(request('sort'))){
            return;
        }

        //RECUPERAMOS LOS DATOS DE LA VARIABLE sort
        $sortFields = explode(',', request('sort'));
        //CONVERTIMOS LOS DATOS DE LA PROPIEDAD allowSort EN UNA COLLECTION PARA UTILIZAR NUEVOS METODOS
        $allowSort = collect($this->allowSort);

        foreach($sortFields as $sortField){

            $direction = 'asc';

            //SUBSTRAEMOS LA PRIMERA LETRA INICIARA EN 0 PERO SOLO OBTENDRA UN SOLO CARACTER
            if(substr($sortField, 0, 1) == '-'){
                $direction = 'desc';
                //TOMAMOS LA CADENA APARTIR DEL INDICE UNO PARA QUE IGNORE EL SIGNO NEGATIVO
                $sortField = substr($sortField, 1);
            }

            //VALIDAMOS QUE EXISTA EL PARAMETRO DENTRO DE LAS OPCIONES DE ORDENAMIENTO PERMITIDAS
            if($allowSort->contains($sortField)){
                //REALIZAMOS LA CONSULTA 
                $query->orderBy($sortField, $direction);
            }
        }

    }

    public function scopeGetOrPaginate(Builder $query){
        //VALIDAMOS SI LA VARIABLE perPage SE ENCUENTRA DEFINIDA EN LA URL
        if(request('perPage')){
            //TRANSFORMAMOS EL VALOR QUE VIENE DE LA URL DE STRING A UN VALOR NUMERICO
            $perPage = intval(request('perPage'));

            //SI LA FUNCION intval TRANSFORMA UN LETRA RETORNA CERO EN CASO TAL QUE SEA CERO EL IF CONSIDERA 0 COMO FALSO Y NO EJECUTA LA INSTRUCCION
            if($perPage){
                return $query->paginate($perPage);
            }
        }

        return $query->get();
    }
}