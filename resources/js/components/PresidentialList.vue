<template>
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Kandidati</h3>

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
                  <th>Kandidat</th>
                </tr>
                <tr v-for="presidential_list in presidential_lists.data" v-bind:key="presidential_list.id">
                  <td>{{ presidential_list.id }}</td>
                  <td>{{ presidential_list.name }}</td>
                  <td>
                      <a href="#" @click="editModal(presidential_list)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deletePresidentialList(presidential_list.id)">
                          <i class="fa fa-trash red"></i>
                      </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <pagination :data="presidential_lists" @pagination-change-page="getResults" align="center"></pagination>
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
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Dodati stranku</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Izmeniti stranku</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updatePresidentialList() : createPresidentialList()">
            <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Kandidat"
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
              toggled: false,
              editmode: false,
              presidential_lists: {},
              form: new Form({
                id: '',
                name: ''
              })
            }
        },

        methods: {

            getResults(page = 1) {
              axios.get('api/presidential_list?page=' + page)
                .then(response => {
                  this.presidential_lists = response.data;
                });
            },

            updatePresidentialList() {
              this.$Progress.start();
              this.form.put('api/presidential_list/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Kandidat uspešno izmenjen!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(presidential_list) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(presidential_list);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deletePresidentialList(id) {
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
                  this.form.delete('api/presidential_list/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Kandidat uspešno obrisan!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadPresidentialList() {
              axios.get('api/presidential_list').then(({ data }) => (this.presidential_lists = data));
            },

            createPresidentialList() {
              this.$Progress.start();

              this.form.post('api/presidential_list')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Kandidati uspešno kreiran!'
                    })

                    this.$Progress.finish();
                })
              .catch(() => {
                  this.$Progress.fail();
              });

            }
        },

        created() {
            this.loadPresidentialList();
            Fire.$on('AfterIsDone', () => {
              this.loadPresidentialList();
            });
        }
    }
</script>
