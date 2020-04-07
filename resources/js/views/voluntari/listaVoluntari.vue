<template>
  <div>
    <div class="filter-container">
      <el-input v-model="query.keyword" placeholder="Nume, email, telefon, adresa.." style="width: 250px;" class="filter-item" @keyup.enter.native="handleFilter" />

      <el-select v-model="query.involvement_direction" clearable placeholder="Directie Implicare" class="filter-item" @change="handleFilter">
        <el-option label="Call Center" value="Dispecer" />
        <el-option label="Medicamente" value="Livrare Medicamente" />
        <el-option label="Alimente" value="Livrare Alimente" />
      </el-select>

      <el-select v-model="query.type" placeholder="Rol voluntar" class="filter-item" clearable @change="handleFilter">

        <el-option label="Livrator Medicamente" value="livrator_medicamente" />
        <el-option label="Livrator Alimente" value="livrator_alimente" />
        <el-option label="Dispecer" value="dispecer" />
        <el-option label="Coordonator voluntari" value="coordonator" />
        <el-option label="Vizitator" value="viewer" />
        <el-option label="Administrator" value="admin" />
      </el-select>

      <el-select v-model="query.verified" clearable placeholder="Status Verificare" class="filter-item" @change="handleFilter">
        <el-option label="Verificat" value="da" />
        <el-option label="Neverificat" value="nu" />
      </el-select>

      <el-select v-model="query.has_traning" clearable placeholder="Status Training" class="filter-item" @change="handleFilter">
        <el-option label="Training efectuat" value="da" />
        <el-option label="Training nefectuat" value="nu" />
      </el-select>

      <el-select v-model="query.has_car" clearable placeholder="Detine masina" class="filter-item" @change="handleFilter">
        <el-option label="Da" value="da" />
        <el-option label="Nu" value="nu" />
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="searchByProximity">
        Cauta
      </el-button>
      <br>
      <!--      <h6>Proximity search</h6>-->
      <div class="el-select filter-item el-select--medium">
        <vue-google-autocomplete
          id="map"
          classname="el-input__inner"
          placeholder="Type address"
          @placechanged="getAddressData"
        />
      </div>
      <el-input v-model="query.radius" type="number" clearable placeholder="Meter radius" style="width: 250px;" class="filter-item" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Search nearby volunteers
      </el-button>
    </div>

    <div class="table-container table-mobile-responsive">
      <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
        <el-table-column align="center" label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>

          </template>
        </el-table-column>

        <el-table-column align="center" label="Voluntar" width="285">
          <template slot-scope="scope">
            <div class="volunteer-name">
              <div class="image">
                <img data-v-152d8d58="" :src="scope.row.user.avatar ? scope.row.user.avatar : 'http://placekitten.com/g/200/300'" alt="user image" class="img-circle">
              </div>
              <div class="name-text">
                <strong>{{ scope.row.name }}</strong><br>
                <span>{{ scope.row.phone }}</span><br>
                <span>{{ scope.row.email }}</span><br>
                <span v-if="scope.row.has_car==='da'"> {{ scope.row.car_plates }}</span>
              </div>
            </div>
          </template>

        </el-table-column>

        <el-table-column align="center" label="Adresa" width="230">
          <template slot-scope="scope">
            <span>Str. {{ scope.row.address }}, </span>
            <span v-if="scope.row.address_no ">Nr. {{ scope.row.address_no }},</span>
            <span v-if="scope.row.address_bl ">Bl. {{ scope.row.address_bl }},</span>
            <span v-if="scope.row.address_sc "> Sc. {{ scope.row.address_sc }},</span>
            <span v-if="scope.row.address_ap ">Ap. {{ scope.row.address_ap }},</span>
            <span v-if="scope.row.city ">{{ scope.row.city }},</span>
            <span v-if="scope.row.county ">{{ scope.row.county }},</span>

          </template>
        </el-table-column>

        <el-table-column align="center" label="Doreste implicare in Dep:">
          <template slot-scope="scope">
            <span v-if="scope.row.involvement_direction==='Call Center'" class="directie-implicare">Call Center</span><br>
            <span v-if="scope.row.involvement_direction==='Alimente'" class="directie-implicare">Livrare Alimente</span><br>
            <span v-if="scope.row.involvement_direction==='Medicamente'" class="directie-implicare">Livrare Medicamente</span><br>

          </template>
        </el-table-column>

        <el-table-column align="center" label="Rol in Organizatie">
          <template slot-scope="scope">
            <span v-if="scope.row.type==='viewer'" class="rol-organziatie" type="success">Voluntar Simplu</span>
            <span v-if="scope.row.type==='livrator_medicamente'" class="rol-organziatie" type="success">Livrator Medicamente</span>
            <span v-if="scope.row.type==='livrator_alimente'" class="rol-organziatie" type="success">Livrator Alimente</span>
            <span v-if="scope.row.type==='dispecer'" class="rol-organziatie" type="success">Dispecer</span>
            <span v-if="scope.row.type==='coordonator'" class="rol-organziatie" type="success">Coordonator voluntari</span>

            <p v-if="scope.row.verified" style="color:green;margin: 3px"><i class="el-icon-success" /> Voluntar verificat</p>
            <p v-else style="color: red;margin: 3px"><i class="el-icon-warning" /> Necesita verificare </p>

            <p v-if="scope.row.has_traning" style="color:green;margin: 3px"><i class="el-icon-success" /> Trainig efectuat</p>
            <p v-else style="color: red;margin: 3px"><i class="el-icon-warning" /> Necesita training </p>

          </template>
        </el-table-column>

        <el-table-column align="center" label="Acorduri legale">
          <template slot-scope="scope">

            <el-tag v-if="scope.row.ip_acord" type="success">Acord ISU Semnat</el-tag>
            <el-tag v-else type="warning">Acord ISU Nesemnat</el-tag>
            <p />
            <el-tag v-if="scope.row.ip_acord_voluntariat" type="success">Acord Voluntariat Semnat</el-tag>
            <el-tag v-else type="warning">Acord Voluntariat Nesemnat</el-tag>

          </template>
        </el-table-column>

        <el-table-column align="center" label="Actions" width="150">
          <template slot-scope="scope">
            <router-link :to="'/voluntari/edit/'+scope.row.user_id">
              <el-button v-role="['admin', 'coordonator']" type="primary" size="small" icon="el-icon-edit">
                Vezi Detalii
              </el-button>
            </router-link>
            <div v-if="scope.row.user.roles[0].name==='admin'">Administrator</div>
          </template>
        </el-table-column>
      </el-table>

      <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
    </div>

  </div>
</template>

<script>
import Resource from '@/api/resource';
import VueGoogleAutocomplete from 'vue-google-autocomplete';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Permission directive

import role from '@/directive/role'; // Permission directive (v-role)
import { mapGetters } from 'vuex';
import { validEmail } from '@/utils/validate';
import axios from 'axios';
const volunteersResource = new Resource('volunteers');
export default {
  name: 'ListaVoluntari',
  components: { Pagination, VueGoogleAutocomplete },
  directives: { waves, permission, role },
  data(){
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error('Please enter the correct email'));
      } else {
        callback();
      }
    };
    return {
      dialogVisible: false,
      list: [],
      loading: false,
      total: 0,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        categories: '',
        status: '',
        type: '',
        has_car: '',
        verified: '',
        has_traning: '',
      },
      volunteerModel: '',
      loadingButton: false,
      judete: [],
      orase: [],
      loginRules: {
        email: [{ required: true, trigger: 'blur', message: 'Adresa de email nu este corecta', validator: validateEmail }],
        name: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        phone: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        city: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        address: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        activation_area: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        involvement_type: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        involvement_direction: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        availability: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
        availability_details: [{ required: true, trigger: 'change', message: 'Campul este obligatoriu' }],
      },
    };
  },
  computed: {
    ...mapGetters([
      'roles',
    ]),
  },
  mounted(){
    this.getList();

    axios.get('https://roloca.coldfuse.io/judete').then(resp => {
      this.judete = resp.data;
    });
  },
  methods: {
    /**
     * When the location found
     * @param {Object} addressData Data of the found location
     * @param {Object} placeResultData PlaceResult object
     * @param {String} id Input container ID
     */
    getAddressData: function(addressData, placeResultData, id) {
      // this.address = addressData;
      // console.log(addressData, placeResultData, id)
      this.query.latitude = addressData.latitude;
      this.query.longitude = addressData.longitude;
    },
    editVolunteerDialog(volunteer){
      this.volunteerModel = volunteer;

      this.getOraseJudet();

      this.dialogVisible = true;
    },
    handleClose(done) {
      this.dialogVisible = false;
    },
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await
      volunteersResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    searchByProximity() {
      // this.
    },
    updateVolunteer(volunteer){
      this.form = volunteer;

      volunteersResource
        .update(this.form.id, this.form)
        .then(response => {
          this.$message({
            message: 'Datele au fost actualizate',
            type: 'success',
            duration: 5 * 1000,
          });
          this.loading = false;
          this.$store.dispatch('user/getInfo');
          this.$router.push('/voluntari/list');
          this.dialogVisible = false;
        })
        .catch(error => {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    getOraseJudet(judet){
      axios.get('https://roloca.coldfuse.io/orase/' + judet ? judet : this.volunteerModel.county).then(resp => {
        this.orase = resp.data;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
  .el-form-item__label{
    margin-bottom: -20px;
  }
  .filter-container, .table-container {
    padding: 10px;
  }

  .el-table .cell{
    word-break: break-word;
  }

  .directie-implicare {

    padding: 5px;
    display: block;
    border: 1px solid #ddd;
    background: #F0475A;
    color:white;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;

  }

  .rol-organziatie {

    padding: 5px;
    display: block;
    border: 1px solid #ddd;
    background: #1990ff;
    color:white;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;

  }

  .volunteer-name {

    .image {

      display: block;
      float: left;
      margin-top: 10px;
    }
      img {

        cursor: pointer;
        width: 50px;
        height: 50px;
        border-radius: 50px;
      }

    .name-text{

      text-align: left;
      margin-left: 10px;
      display: block;
      float: left;
    }

    }

    @media(max-width: 800px) {

      table, thead, tbody, th, td, tr {
        display: block;
      }

      table{
        width: 310px!important;
      }

      /* Hide table headers (but not display: none;, for accessibility) */
      thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }

      tr { border: 1px solid #ccc; }

      td {
        /* Behave  like a "row" */
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
      }

      td:before {
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
        top: 6px;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
      }

    }

    @media(max-width: 1700px) {
      .mobile-resp{
        margin: 10px;
      }
    }

</style>
