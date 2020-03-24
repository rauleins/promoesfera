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
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Nombres</label>
                          <input type="text" class="form-control" v-model="nombre" onkeypress="return sololetras(event)">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errornombre" :key ="err">{{err}}</div>
                          </div>
                      </div> 
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Apellidos</label>
                          <input type="text" class="form-control" v-model="apellido" onkeypress="return sololetras(event)">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorapellido" :key ="err">{{err}}</div>
                          </div>
                      </div> 
                  </div>
                  <div class="col-xl-4 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Cédula</label>
                          <input type="text" class="form-control" v-model="cedula" onkeypress="return solonumeros(event)">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorcedula" :key ="err">{{err}}</div>
                          </div>
                      </div> 
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Dirección</label>
                          <input type="text" class="form-control" v-model="direccion">
                      </div> 
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Teléfono</label>
                          <input type="text" class="form-control" v-model="telefono" onkeypress="return solonumeros(event)" maxlength="9">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errortelefono" :key ="err">{{err}}</div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Celular</label>
                          <input type="text" class="form-control" v-model="celular" onkeypress="return solonumeros(event)" maxlength="10">
                      </div>
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Correo electrónico</label>
                          <input type="text" class="form-control" v-model="email">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in erroremail" :key ="err">{{err}}</div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Estado</label>
                          <select class="form-control" v-model="estado">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Contraseña <span v-if="tipomodal==2" style="font-size: 10px;font-weight: bold;font-family: unset;">(Dejar vacío si no se cambia)</span></label>
                          <input type="password" name="passusuariob" autocomplete="current-passwordb" class="form-control" v-model="password">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorpassword" :key ="err">{{err}}</div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-6 col-lg-12 col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Repetir Contraseña</label>
                          <input type="password" name="repassusuariob" autocomplete="recurrent-passwordb" class="form-control" v-model="repassword">
                          <div v-show="error">
                              <div class="invalid-feedback" style="display:block;" v-for="err in errorrepassword" :key ="err">{{err}}</div>
                          </div>
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
        nombre:"",
        apellido:"",
        cedula:"",
        direccion:"",
        telefono:"",
        celular:"",
        estado:1,
        email:"",
        password:"",
        repassword:"",
        //errores
        errorcedulaRuc: [],
        errorrazonsocial_nombres: [],
        errorrazoncomercial_apellidos: [],
        errordireccion: [],
        erroreMail: [],
        errorcelular: [],
        errorciudad: [],
        
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
        switch(tipo){
            case 'agregar': {
                this.titulomodal="Agregar Usuarios";
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
                this.direccion_entrega = 1;
                this.telefono = "";
                this.celular = "";
                this.eMail = "";
                this.ciudad = "";
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
                axios.delete("/usuarios/eliminar/"+id);
                this.listar(1,this.buscar);
                alertify.success('Registro Eliminado');
            }
        });
    },
    validar(){
        this.error = 0;
        this.errorcedulaRuc  =  [];
        this.errorrazonsocial_nombres  =  [];
        this.errorrazoncomercial_apellidos  =  [];
        this.errordireccion  =  [];
        this.erroreMail  =  [];
        this.errorcelular  =  [];
        this.errorciudad =  [];
  
        if(!this.cedulaRuc){
          this.errorcedulaRuc.push("Ingrese su Cedula");
          this.error = 1;
        }
        if(!this.razonsocial_nombres){
          this.errorrazonsocial_nombres.push("Ingrese su Nombre o Razon Social");
          this.error = 1;
        }
        if(!this.razoncomercial_apellidos){
          this.errorrazoncomercial_apellidos.push("Ingrese su Apellidoo Razon Comercial");
          this.error = 1;
        }
        if(!this.direccion){
          this.errordireccion.push("Ingrese su Direccion");
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
      window.open("/usuario/excel", "_top");
    },
    pdf() {
      window.open("/usuario/pdf", "_top");
    }
  },
  mounted() {
    this.listar(1, this.buscar);
  }
};
</script>
