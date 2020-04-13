<template>
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Gradovi/opštine</h3>

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
                  <th>ID</th>
                  <th>Ime grada/opštine</th>
                  <th>Okrug</th>
                  <th>Broj birača</th>
                  <th>Broj mandata</th>
                </tr>
                <tr v-for="town in towns.data" v-bind:key="town.id">
                  <td>{{ town.id }}</td>
                  <td>{{ town.name }}</td>
                  <td>{{ town.district.name }}</td>
                  <td>{{ town.electoral_number }}</td>
                  <td>{{ town.councilor_number }}</td>
                  <td>
                      <a href="#" @click="editModal(town)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteTown(town.id)">
                          <i class="fa fa-trash red"></i>
                      </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Dodati grad/opštinu</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Izmeniti grad/opštinu</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updateTown() : createTown()">
            <div class="modal-body">
                <div class="form-group">
                    <select name="district_id" class="form-control" v-model="form.district_id" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Izaberite okrug</option>
                        <option v-for="district in districts" v-bind:key="district.id" :value="district.id" v-if="district">{{ district.name }}</option>
                    </select>
                    <has-error :form="form" field="district_id"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.electoral_number" type="text" name="electoral_number" placeholder="Broj birača"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('electoral_number') }">
                  <has-error :form="form" field="electoral_number"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.councilor_number" type="text" name="councilor_number" placeholder="Broj mandata"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('councilor_number') }">
                  <has-error :form="form" field="councilor_number"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Ime grada/opštine"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
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
              districts: {},
              towns: {},
              form: new Form({
                id: '',
                district_id: '',
                electoral_number: '',
                name: '',
                councilor_number: ''
              })
            }
        },

        methods: {

            updateTown() {
              this.$Progress.start();
              this.form.put('api/town/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Grad/Opština uspešno izmenjen/a!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(town) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(town);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteTown(id) {
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
                  this.form.delete('api/town/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Grad/Opština uspešno obrisan/a!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadTowns() {
              axios.get('api/town').then(({ data }) => (this.towns = data));
            },

            loadDistricts() {
              axios.get('api/district').then(response => { this.districts = response.data.data; });
            },

            createTown() {
              this.$Progress.start();

              this.form.post('api/town')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Grad/Opština uspešno kreiran/a!'
                    })

                    this.$Progress.finish();
                })
              .catch(() => {
                  this.$Progress.fail();
              });

            }
        },

        created() {
            this.loadDistricts();
            this.loadTowns();
            Fire.$on('AfterIsDone', () => {
              this.loadTowns();
            });
        }
    }
</script>
