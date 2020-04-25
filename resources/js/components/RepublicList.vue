<template>
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Stranke</h3>

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
                  <th>Stranka</th>
                  <th>Broj kandidata</th>
                  <th>Manjinska</th>
                </tr>
                <tr v-for="republic_list in republic_lists.data" v-bind:key="republic_list.id">
                  <td>{{ republic_list.id }}</td>
                  <td>{{ republic_list.name }}</td>
                  <td>{{ republic_list.candidates_number }}</td>
                  <td v-if="republic_list.minority == 1">✔</td>
                  <td v-else></td>
                  <td>
                      <a href="#" @click="editModal(republic_list)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteRepublicList(republic_list.id)">
                          <i class="fa fa-trash red"></i>
                      </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <pagination :data="republic_lists" @pagination-change-page="getResults" align="center"></pagination>
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
            <form @submit.prevent="editmode ? updateRepublicList() : createRepublicList()">
            <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Stranka"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.candidates_number" type="text" name="candidates_number" placeholder="Broj kandidata"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('candidates_number') }">
                  <has-error :form="form" field="candidates_number"></has-error>
                </div>
                <toggle-button v-show="editmode" name="minority" v-model="form.minority"
                 @change="toggled = $event.value"
                 :labels="{checked: 'Uključeno', unchecked: 'Isključeno'}" 
                 :width="75"/> 
                <toggle-button v-show="!editmode" name="minority" 
                 :labels="{checked: 'Uključeno', unchecked: 'Isključeno'}" 
                 :width="75"/> Nacionalna manjina
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
              republic_lists: {},
              form: new Form({
                id: '',
                name: '',
                minority: '',
                candidates_number: ''
              })
            }
        },

        methods: {

            getResults(page = 1) {
              axios.get('api/republic_list?page=' + page)
                .then(response => {
                  this.republic_lists = response.data;
                });
            },

            updateRepublicList() {
              this.$Progress.start();
              this.form.put('api/republic_list/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Stranka uspešno izmenjena!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(republic_list) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(republic_list);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteRepublicList(id) {
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
                  this.form.delete('api/republic_list/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Stranka uspešno obrisana!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadRepublicList() {
              axios.get('api/republic_list').then(({ data }) => (this.republic_lists = data));
            },

            createRepublicList() {
              this.$Progress.start();

              this.form.post('api/republic_list')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Stranka uspešno kreirana!'
                    })

                    this.$Progress.finish();
                })
              .catch(() => {
                  this.$Progress.fail();
              });

            }
        },

        created() {
            this.loadRepublicList();
            Fire.$on('AfterIsDone', () => {
              this.loadRepublicList();
            });
        }
    }
</script>
