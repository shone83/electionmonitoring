<template>
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Korisnici</h3>

              <div class="box-tools float-right">
                <button type="submit" class="btn btn-primary btn-sm" @click="newModal">Dodati 
                    <i class="fa fa-user-plus fa-fw"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Ime</th>
                </tr>
                <tr v-for="district in districts.data">
                  <td>{{ district.id }}</td>
                  <td>{{ district.name }}</td>
                  <td>
                      <a href="#" @click="editModal(district)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteDistrict(district.id)">
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
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Dodati nov okrug</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Izmeniti okrug</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updateDistrict() : createDistrict()">
            <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Ime okruga"
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
              form: new Form({
                id: '',
                name: ''
              })
            }
        },

        methods: {

            updateDistrict() {
              this.$Progress.start();
              this.form.put('api/district/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Okrug uspešno izmenjen!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(district) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(district);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteDistrict(id) {
              Swal.fire({
                title: 'Dali ste sigurni?',
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
                  this.form.delete('api/district/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Okrug uspešno obrisan!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadDistricts() {
              axios.get('api/district').then(({ data }) => (this.districts = data));
            },

            createDistrict() {
              this.$Progress.start();

              this.form.post('api/district')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Okrug uspešno kreiran!'
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
            Fire.$on('AfterIsDone', () => {
              this.loadDistricts();
            });
        }
    }
</script>
