<template>
    <aside class="left-sidebar">
      <div class="scroll-sidebar">
        <div class="user-profile" style="background: url(imagenes/user-info.jpg) no-repeat;">
            <div class="profile-img"> <img src="imagenes/profile.png" alt="user" /> </div>
            <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{nombre_usuario}}</a>
                <div class="dropdown-menu animated flipInY">
                    <div class="dropdown-divider"></div> <a href="/logout" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Cerrar sesión</a>
                </div>
            </div>
        </div>
        <nav class="sidebar-nav">
          <ul id="sidebarnav">
            <li>
              <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-home"></i><span class="hide-menu">ADMINISTRACION </span></a>
              <ul aria-expanded="false" class="collapse">
                  <li><router-link to="/">Usuarios</router-link></li>
              </ul>
            </li>
            <li>
              <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-home"></i><span class="hide-menu">VENTAS </span></a>
              <ul aria-expanded="false" class="collapse">
                  <li><router-link to="/persona">Clientes</router-link></li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
</template>
<script>
import axios from "axios";
import Swal from 'sweetalert2';
import $ from "jquery";
var moment = require('moment');
moment.locale("es");
export default {
  data() {
    return {
      nombre_usuario:"",
      rol_usuario:"",
      correo_usuario:"",
    };
  },
  methods: {
    recuperar() {
      axios.get("/sesion/recuperar").then(res => {
        if(res.data){
          this.nombre_usuario = res.data.nombre;
          this.correo_usuario= res.data.email;
        }else{
          //$(".cerrarsesion").click();
        }
      }).catch(err => {
          //$(".cerrarsesion").click();
      });
    },
  },
  mounted() {
    this.recuperar();
  }
};
</script>
<style>
  .router-link-exact-active{
    background: #1e88e5;
    color:#fff!important;
  }
  .router-link-exact-active i{
    color:#fff!important;
  }
</style>