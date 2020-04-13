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
                  <th>Korisničko ime</th>
                  <th>Grad/Opština</th>
                  <th>Status</th>
                  <th>Email</th>
                  <th>Registrovan</th>
                </tr>
                <tr v-for="user in users.data" v-bind:key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.username }}</td>
                  <td>{{ user.town.name }}</td>
                  <td>{{ user.role.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.created_at | myDate }}</td>
                  <td>
                      <a href="#" @click="editModal(user)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteUser(user.id)">
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
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Dodati novog korisnika</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Izmeniti korisnika</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updateUser() : createUser()">
            <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Ime i prezime"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.username" type="text" name="username" placeholder="Korisničko ime"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                  <has-error :form="form" field="username"></has-error>
                </div>
                <div class="form-group">
                    <select name="town_id" class="form-control" v-model="form.town_id" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Izaberite grad/opštinu</option>
                        <option v-for="town in towns" v-bind:key="town.id" :value="town.id" v-if="town">{{ town.name }}</option>
                    </select>
                    <has-error :form="form" field="town_id"></has-error>
                </div>
                <div class="form-group">
                    <select name="role_id" class="form-control" v-model="form.role_id" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Izaberite status</option>
                        <option v-for="role in roles" v-bind:key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                    <has-error :form="form" field="role_id"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.email" type="email" name="email" placeholder="Email"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                  <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.password" type="password" name="password" placeholder="Lozinka"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                  <has-error :form="form" field="password"></has-error>
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
              roles: {},
              users: {},
              form: new Form({
                id: '',
                name: '',
                username: '',
                town_id: '',
                role_id: '',
                email: '',
                password: ''
              })
            }
        },

        methods: {

            updateUser() {
              this.$Progress.start();
              this.form.put('api/user/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Korisnik uspešno izmenjen!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(user) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(user);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteUser(id) {
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
                  this.form.delete('api/user/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Korisnik uspešno obrisan!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadUsers() {
              axios.get('api/user').then(({ data }) => (this.users = data));
            },

            loadTowns() {
              axios.get('api/town').then(response => { this.towns = response.data.data; });
            },

            loadRoles() {
              axios.get('api/role').then(({ data }) => (this.roles = data));
            },

            createUser() {
              this.$Progress.start();

              this.form.post('api/user')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Korisnik uspešno kreiran!'
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
            this.loadRoles();
            this.loadUsers();
            Fire.$on('AfterIsDone', () => {
              this.loadUsers();
            });
        }
    }
</script>
