<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 justify-content-end">
                <div class="btn-group" style="float: right;">
                    <button type="button" class="btn btn-primary ml-2" @click="abrir('agregar')">Agregar Cliente</button>
                    <button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Exportar PDF" @click="pdf()"><i style="color:#d40a0a" class="fas fa-file-pdf"></i></button>
                    <button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Exportar Excel" @click="excel()"><i style="color:#107c42;"></i></button>
                </div>
              <div class="input-group mb-3" style="width: 20em;float: right;">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Buscar.."
                  aria-describedby="basic-addon2"
                  v-model="buscar"
                  @keyup="listar(1,buscar)"
                />
                <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon2">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Cédula</th>
                  <th>Persona</th>
                  <th>Email</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody v-if="recupera.length">
                <tr v-for="(tr,index) in recupera" :key="index">
                  <td>{{tr.cedulaRuc}}</td>
                  <td>{{tr.razonsocial_nombres}} {{tr.razoncomercial_apellidos}}</td>
                  <td>{{tr.eMail}}</td>
                  <td>{{tr.contacto}}</td>
                  <td class="pointer acciones">
                    <i class="fa fa-edit" @click="abrir('editar', tr)"></i> |
                    <i class="fa fa-trash" @click="eliminar(tr.id)"></i>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="99">
                    <div class="alert alert-warning text-center" role="alert">SIN REGISTROS</div>
                  </td>
                </tr>
              </tbody>
            </table>
            <nav aria-label="Page navigation example">
              <ul class="pagination justify-content-center">
                <li class="page-item" v-if="paginacion.current_page > 1">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(paginacion.current_page - 1)">&laquo;</a>
                </li>
                <li class="page-item" v-else>
                  <a class="page-link" disabled>&laquo;</a>
                </li>
                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                </li>
                <li class="page-item" v-if="paginacion.current_page < paginacion.last_page">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(paginacion.current_page + 1)">&raquo;</a>
                </li>
                <li class="page-item" v-else>
                  <a class="page-link" disabled>&raquo;</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" :class="{'abrirmodal':abrirmodal}" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{titulomodal}}</h5>
                <button type="button" class="close" @click="cerrar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <form action="">
                <div class="row form-material">
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Razón social - Nombres </label>
                          <input type="text" class="form-control" v-model="razonsocial_nombres">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorrazonsocial_nombres" :key ="err">{{err}}</div>
                          </div>
                      </div> 
                  </div>
                  <div class="col-xl-3 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Tipo Cliente</label>
                          <select class="form-control" v-model="tipo">
                              <option value="">Seleccione un Tipo</option>
                              <option value="Natural">Natural</option>
                              <option value="Juridica">Juridica</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-xl-3 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Cédula-Ruc</label>
                          <input type="text" class="form-control" v-model="cedulaRuc" onkeypress="return solonumeros(event)">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorcedulaRuc" :key ="err">{{err}}</div>
                          </div>
                      </div> 
                  </div>
                  <div class="col-lg-4 col-xs-12 col-sm-12">
                      <div class="form-group">
                          <label for="exampleFormControlInput1">Categoría</label>
                          <select class="form-control" v-model="categoria">
                              <option value="">SELECCIONE CATEGORÍA</option>
                              <option value="ALIMENTOS Y BEBIDAS ">ALIMENTOS Y BEBIDAS</option>
                              <option value="BANCA Y FINANZAS ">BANCA Y FINANZAS</option>
                              <option value="FARMACEUTICAS ">FARMACEUTICAS</option>
                              <option value="BARES Y RESTAURANTES ">BARES Y RESTAURANTES</option>
                              <option value="DISTRIBUIDORES ">DISTRIBUIDORES</option>
                              <option value="AGENCIAS DE PUBLICIDAD ">AGENCIAS DE PUBLICIDAD</option>
                              <option value="INDUSTRIA PETROQUÍMICA ">INDUSTRIA PETROQUÍMICA</option>
                              <option value="EDUCACIÓN  ">EDUCACIÓN </option>
                              <option value="TECNOLOGÍA ">TECNOLOGÍA</option>
                              <option value="HOSPITALIDAD ">HOSPITALIDAD</option>
                              <option value="BELLEZA ">BELLEZA</option>
                              <option value="CONSUMO ">CONSUMO</option>
                              <option value="GOBIERNO ">GOBIERNO</option>
                              <option value="CONSTRUCCIÓN ">CONSTRUCCIÓN</option>
                              <option value="SALUD  ">SALUD </option>
                              <option value="INDUSTRIA AUTOMOTRIZ ">INDUSTRIA AUTOMOTRIZ</option>
                          </select>
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorcategoria" :key ="err">{{err}}</div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Ciudad</label>
                          <input type="text" class="form-control" v-model="ciudad" onkeypress="return sololetras(event)">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorciudad" :key ="err">{{err}}</div>
                          </div>
                      </div> 
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Teléfonos</label>
                          <input type="text" class="form-control" v-model="telefono">
                      </div>
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Dirección</label>
                          <input type="text" class="form-control" v-model="direccion">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errordireccion" :key ="err">{{err}}</div>
                          </div>
                      </div> 
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Dirección Entrega</label>
                          <input type="text" class="form-control" v-model="direccion_entrega">
                      </div> 
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Contacto</label>
                          <input type="text" class="form-control" v-model="contacto" onkeypress="return sololetras(event)">
                      </div> 
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Celular</label>
                          <input type="text" class="form-control" v-model="celular" onkeypress="return solonumeros(event)" maxlength="30">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorcelular" :key ="err">{{err}}</div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">e-Mail</label>
                          <input type="email" class="form-control" v-model="eMail">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in erroreMail" :key ="err">{{err}}</div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Contacto 1</label>
                          <input type="text" class="form-control" v-model="contacto1" onkeypress="return sololetras(event)">
                      </div> 
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Celular 1</label>
                          <input type="text" class="form-control" v-model="celular1" onkeypress="return solonumeros(event)" maxlength="30">
                      </div>
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">e-Mail 1</label>
                          <input type="email" class="form-control" v-model="email1">
                      </div>
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Contacto 2</label>
                          <input type="text" class="form-control" v-model="contacto2" onkeypress="return sololetras(event)">
                      </div> 
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Celular 2</label>
                          <input type="text" class="form-control" v-model="celular2" onkeypress="return solonumeros(event)" maxlength="30">
                      </div>
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">e-Mail 2</label>
                          <input type="email" class="form-control" v-model="email2">
                      </div>
                  </div>
                  
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  @click="cerrar()">Cerrar</button>
                <button type="button" class="btn btn-primary" v-if="tipomodal==1" @click="guardar()">Guardar</button>
                <button type="button" class="btn btn-primary" v-if="tipomodal==2" @click="editar()">Editar</button>
            </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Swal from 'sweetalert2';
var moment = require('moment');
moment.locale("es");
export default {
  data() {
    return {
        //datos obligatorios
        recupera: [],
        buscar: "",
        id: null,
        pagina: 1,
        error: null,
        accion: null,
        paginacion: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0
        },
        offset:3,
        abrirmodal:0,
        titulomodal:"",
        tipomodal:null,
        error:0,
        id:0,
        //campos
        cedulaRuc:"",
        razonsocial_nombres:"",
        razoncomercial_apellidos:"",
        tipo:"",
        contacto :"",
        direccion:"",
        direccion_entrega:"",
        telefono:"",
        celular:"",
        eMail:"",
        ciudad:"",
        categoria:"",
        contacto1:"",
        email1:"",
        celular1:"",
        contacto2:"",
        email2:"",
        celular2:"",
        //errores
        errorcedulaRuc: [],
        errorrazonsocial_nombres: [],
        errordireccion: [],
        erroreMail: [],
        errorcelular: [],
        errorciudad: [],
        errorcategoria: [],
        
    };
  },
  computed: {
    // No mover obligatorios
    isActived() {
      return this.paginacion.current_page;
    },
    pagesNumber() {
      if (!this.paginacion.to) {
        return [];
      }
      var from = this.paginacion.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.paginacion.last_page) {
        to = this.paginacion.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    fechaalerta(){
      return moment().add(91, 'days').format('YYYY-MM-DD hh:ss');
    },
    fechaalerta1(){
      return moment().add(182, 'days').format('YYYY-MM-DD hh:ss');
    }
  },
  filters:{
    fechaformato(data){
      return moment(data).format('LL');
    }
  },
  methods: {
    // No mover obligatorios
    cambiarPagina(page){
      this.paginacion.current_page = page;
      this.listar(page,this.buscar);
    },
    //metodos cambiantes
    listar(pagina, buscar) {
      axios.get("/persona/listar?buscar=" + buscar + "&page=" + pagina).then(res => {
        this.recupera = res.data.datos.data;
        this.paginacion= res.data.paginacion;
      }).catch(err => {
        console.log(err);
      });
    },
    abrir(tipo, datos){
        this.errorcedulaRuc  =  [];
        this.errorrazonsocial_nombres  =  [];
        this.errorrazoncomercial_apellidos  =  [];
        this.errordireccion  =  [];
        this.erroreMail  =  [];
        this.errorcelular  =  [];
        this.errorciudad =  [];
        this.errorcategoria =  [];
        switch(tipo){
            case 'agregar': {
                this.titulomodal="Agregar Cliente";
                this.abrirmodal=1;
                this.tipomodal=1;
                this.id = null;
                //opcionales
                this.cedulaRuc = "";
                this.razonsocial_nombres = "";
                this.razoncomercial_apellidos = "";
                this.tipo = "";
                this.contacto  = "";
                this.direccion = "";
                this.direccion_entrega= "";
                this.telefono = "";
                this.celular = "";
                this.eMail = "";
                this.ciudad = "";
                this.categoria="",
                this.contacto1 = "";
                this.email1 = "";
                this.celular1 = "";
                this.contacto2 = "";
                this.email2 = "";
                this.celular2 = "";

                
                break;
            }
            case 'editar': {
                this.titulomodal="Editar Usuario";
                this.abrirmodal=1;
                this.tipomodal=2;
                //opcionales
                this.id = datos.id;
                this.cedulaRuc = datos.cedulaRuc ;
                this.razonsocial_nombres = datos.razonsocial_nombres ;
                this.razoncomercial_apellidos = datos.razoncomercial_apellidos ;
                this.tipo = datos.tipo ;
                this.contacto  = datos.contacto ;
                this.direccion = datos.direccion ;
                this.direccion_entrega = datos.direccion_entrega;
                this.telefono = datos.telefono ;
                this.celular = datos.celular ;
                this.eMail = datos.eMail;
                this.ciudad = datos.ciudad ;
                this.categoria= datos.categoria;
                this.contacto1 = datos.contacto1 ;
                this.email1 = datos.email1 ;
                this.celular1 = datos.celular1 ;
                this.contacto2 = datos.contacto2 ;
                this.email2 = datos.email2 ;
                this.celular2 = datos.celular2 ;
                break;
            }
        }
    },
    guardar(){
        if(this.validar()){return;}
        axios.post("/persona/guardar",{
          cedulaRuc:this.cedulaRuc ,
          razonsocial_nombres:this.razonsocial_nombres ,
          razoncomercial_apellidos:this.razoncomercial_apellidos ,
          tipo:this.tipo ,
          contacto:this.contacto  ,
          direccion:this.direccion ,
          direccion_entrega:this.direccion_entrega ,
          telefono:this.telefono ,
          celular:this.celular ,
          eMail:this.eMail ,
          ciudad:this.ciudad ,
          contacto1:this.contacto1 ,
          email1:this.email1 ,
          celular1:this.celular1 ,
          contacto2:this.contacto2 ,
          email2:this.email2 ,
          celular2:this.celular2 ,
        }).then(res => {
            this.cerrar();
            this.listar(1,this.buscar);
            alertify.success('Registro Guardado');
        }).catch(err => {
          console.log(err);
        });
    },
    editar(){
        if(this.validar()){return;}
        axios.put("/persona/editar",{
          id: this.id,
          cedulaRuc:this.cedulaRuc ,
          razonsocial_nombres:this.razonsocial_nombres ,
          razoncomercial_apellidos:this.razoncomercial_apellidos ,
          tipo:this.tipo ,
          contacto:this.contacto  ,
          direccion:this.direccion ,
          direccion_entrega:this.direccion_entrega ,
          telefono:this.telefono ,
          celular:this.celular ,
          eMail:this.eMail ,
          ciudad:this.ciudad ,
          categoria:this.categoria,
          contacto1:this.contacto1 ,
          email1:this.email1 ,
          celular1:this.celular1 ,
          contacto2:this.contacto2 ,
          email2:this.email2 ,
          celular2:this.celular2 ,
        }).then(res => {
          this.cerrar();
          this.listar(1,this.buscar);
          alertify.success('Registro Actualizado');
        }).catch(err => {
          console.log(err);
        });
    },
    cerrar(){
        this.abrirmodal=0;
    },
    eliminar(id){
        Swal.fire({
            title: 'Eliminar registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Eliminar'
        }).then((result) => {
            if (result.value) {
                axios.delete("/persona/eliminar/"+id);
                this.listar(1,this.buscar);
                alertify.success('Registro Eliminado');
            }
        });
    },
    validar(){
        this.error = 0;
        this.errorcedulaRuc  =  [];
        this.errorrazonsocial_nombres  =  [];
        this.errordireccion  =  [];
        this.erroreMail  =  [];
        this.errorcelular  =  [];
        this.errorciudad =  [];
        this.errorcategoria =  [];
  
        if(!this.cedulaRuc){
          this.errorcedulaRuc.push("Ingrese su Cedula");
          this.error = 1;
        }
        if(!this.razonsocial_nombres){
          this.errorrazonsocial_nombres.push("Ingrese su Nombre o Razon Social");
          this.error = 1;
        }
        
        if(!this.direccion){
          this.errordireccion.push("Ingrese su Direccion");
          this.error = 1;
        }
        if(!this.categoria){
          this.errorcategoria.push("Ingrese una Categoria");
          this.error = 1;
        }
        if(!this.eMail){
          this.erroreMail.push("Ingrese su Correo");
          this.error = 1;
        }
        if(!this.celular){
          this.errorcelular.push("Ingrese su Celular");
          this.error = 1;
        }
        if(!this.ciudad){
          this.errorciudad.push("Ingrese su Ciudad");
          this.error = 1;
        }
        
        return this.error;
    },
    //funciones export archivos
    excel() {
      window.open("/persona/excel", "_top");
    },
    pdf() {
      window.open("/persona/pdf", "_top");
    }
  },
  mounted() {
    this.listar(1, this.buscar);
  }
};
</script>
