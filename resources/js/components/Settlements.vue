<template>
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Mesni odbori</h3>

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
                  <th>Mesni odbor</th>
                  <th>Grad/Opština</th>
                </tr>
                <tr v-for="settlement in settlements.data" v-bind:key="settlement.id">
                  <td>{{ settlement.id }}</td>
                  <td>{{ settlement.name }}</td>
                  <td>{{ settlement.town.name }}</td>
                  <td>
                      <a href="#" @click="editModal(settlement)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteSettlement(settlement.id)">
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
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Dodati mesni odbor</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Izmeniti mesni odbor</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updateSettlement() : createSettlement()">
            <div class="modal-body">
                <div class="form-group">
                    <select name="town_id" class="form-control" v-model="form.town_id" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Izaberite grad/opštinu</option>
                        <option v-for="town in towns" v-bind:key="town.id" :value="town.id" v-if="town">{{ town.name }}</option>
                    </select>
                    <has-error :form="form" field="town_id"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Mesni odbor"
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
              towns: {},
              settlements: {},
              form: new Form({
                id: '',
                town_id: '',
                name: ''
              })
            }
        },

        methods: {

            updateSettlement() {
              this.$Progress.start();
              this.form.put('api/settlement/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Mesni odbor uspešno izmenjen!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(settlement) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(settlement);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteSettlement(id) {
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
                  this.form.delete('api/settlement/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Mesni odbor uspešno obrisan!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadSettlements() {
              axios.get('api/settlement').then(({ data }) => (this.settlements = data));
            },

            loadTowns() {
              axios.get('api/town').then(response => { this.towns = response.data.data; });
            },

            createSettlement() {
              this.$Progress.start();

              this.form.post('api/settlement')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Mesni odbor uspešno kreiran!'
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
            Fire.$on('AfterIsDone', () => {
              this.loadSettlements();
            });
        }
    }
</script>
