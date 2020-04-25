<template>
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Izborne jedinice</h3>

              <div class="box-tools float-right">
                <button type="submit" class="btn btn-primary btn-sm" @click="newModal">Dodati 
                    <i class="fa fa-plus fa-fw"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Izborne jedinice</th>
                  <th>Mesni odbor</th>
                  <th>Grad/Opština</th>
                  <th>Očekivani rezultat</th>
                  <th>Kapilarni glasovi</th>
                </tr>
                <tr v-for="electoral_unit in electoral_units.data" v-bind:key="electoral_unit.id">
                  <td>{{ electoral_unit.name }}</td>
                  <td>{{ electoral_unit.settlement.name }}</td>
                  <td>{{ electoral_unit.town.name }}</td>
                  <td>{{ electoral_unit.expected_result }}</td>
                  <td>{{ electoral_unit.capillary }}</td>
                  <td>
                      <a href="#" @click="editModal(electoral_unit)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteElectoralUnit(electoral_unit.id)">
                          <i class="fa fa-trash red"></i>
                      </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <pagination :data="electoral_units" @pagination-change-page="getResults" align="center"></pagination>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Dodati izbornu jedinicu</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Izmeniti izbornu jedinicu</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updateElectoralUnit() : createElectoralUnit()">
            <div class="modal-body">
                <div class="form-group">
                    <input v-model="form.name" type="text" name="name" placeholder="Izborna jedinica"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                    <select name="settlement_id" class="form-control" v-model="form.settlement_id" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Izaberite mesni odbor</option>
                        <option v-for="settlement in all_settlements" v-bind:key="settlement.id" :value="settlement.id">{{ settlement.name }}</option>
                    </select>
                    <has-error :form="form" field="settlement_id"></has-error>
                </div>
                <div class="form-group">
                    <select name="town_id" class="form-control" v-model="form.town_id" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Izaberite grad/opštinu</option>
                        <option v-for="town in towns" v-bind:key="town.id" :value="town.id">{{ town.name }}</option>
                    </select>
                    <has-error :form="form" field="town_id"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.expected_result" type="text" name="expected_result" placeholder="Očekivani rezultat"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('expected_result') }">
                  <has-error :form="form" field="expected_result"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.capillary" type="text" name="capillary" placeholder="Kapilarni glasovi"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('capillary') }">
                  <has-error :form="form" field="capillary"></has-error>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
              <button v-show="editmode" type="submit" class="btn btn-info">Izmeniti</button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">Dodati</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
              editmode: false,
              towns: {},
              all_settlements: {},
              electoral_units: {},
              form: new Form({
                id: '',
                town_id: '',
                settlement_id: '',
                name: '',
                expected_result: '',
                capillary: ''
              })
            }
        },

        methods: {

            getResults(page = 1) {
                axios.get('api/electoral_unit?page=' + page)
                  .then(response => {
                    this.electoral_units = response.data;
                  });
              },

            updateElectoralUnit() {
              this.$Progress.start();
              this.form.put('api/electoral_unit/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Izborna jedinica uspešno izmenjena!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(electoral_unit) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(electoral_unit);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteElectoralUnit(id) {
              Swal.fire({
                title: 'Da li ste sigurni?',
                text: "Nećete moći da vratite podatke!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#808080',
                confirmButtonText: 'Da, obrisati!',
                cancelButtonText: 'Odustati'
              }).then((result) => {
                if (result.value) {
                  // Send request to the server
                  this.form.delete('api/electoral_unit/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Izborna jedinica uspešno obrisana!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadTowns() {
              axios.get('api/town').then(response => { this.towns = response.data.data; });
            },

            loadSettlements() {
              axios.get('api/all_settlements').then(({ data }) => (this.all_settlements = data));
            },

            loadElectoralUnits() {
              axios.get('api/electoral_unit').then(({ data }) => (this.electoral_units = data));
            },

            createElectoralUnit() {
              this.$Progress.start();

              this.form.post('api/electoral_unit')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Izborna jedinica uspešno kreirana!'
                    })

                    this.$Progress.finish();
                })
              .catch(() => {
                  this.$Progress.fail();
              });

            }
        },

        created() {
            this.loadTowns();
            this.loadSettlements();
            this.loadElectoralUnits();
            Fire.$on('AfterIsDone', () => {
              this.loadElectoralUnits();
            });
        }
    }
</script>
