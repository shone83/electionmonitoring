<template>
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lokalna lista</h3>

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
                  <th>Lokalna lista</th>
                  <!-- <th>Manjina</th> -->
                  <th>Grad/Opština</th>
                </tr>
                <tr v-for="local_list in local_lists.data" v-bind:key="local_list.id">
                  <td>{{ local_list.id }}</td>
                  <td>{{ local_list.name }}</td>
                  <!-- <td v-if="local_list.minority == 1">✔</td>
                  <td v-else></td> -->
                  <td>{{ local_list.town.name }}</td>
                  <td>
                      <a href="#" @click="editModal(local_list)">
                          <i class="fa fa-edit"></i>
                      </a>
                      /
                      <a href="#" @click="deleteLocalList(local_list.id)">
                          <i class="fa fa-trash red"></i>
                      </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <pagination :data="local_lists" @pagination-change-page="getResults" align="center"></pagination>
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
            <form @submit.prevent="editmode ? updateLocalList() : createLocalList()">
            <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Stranka"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                    <select name="town_id" class="form-control" v-model="form.town_id" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Izaberite grad/opštinu</option>
                        <option v-for="town in towns" v-bind:key="town.id" :value="town.id">{{ town.name }}</option>
                    </select>
                    <has-error :form="form" field="town_id"></has-error>
                </div>
                <!-- <toggle-button v-show="editmode" name="minority" v-model="form.minority"
                 @change="toggled = $event.value"
                 :labels="{checked: 'Uključeno', unchecked: 'Isključeno'}" 
                 :width="75"/> 
                <toggle-button v-show="!editmode" name="minority" 
                 :value="false"
                 @change="toggled = $event.value"
                 :labels="{checked: 'Uključeno', unchecked: 'Isključeno'}" 
                 :width="75"/> Nacionalna manjina -->
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
              // toggled: false,
              editmode: false,
              towns: {},
              local_lists: {},
              form: new Form({
                id: '',
                town_id: '',
                name: ''
                // minority: ''
              })
            }
        },

        // mounted() {
        //     var itemIndex = 0;
        //     setInterval(() => {
        //       this.updateItemValue(itemIndex)
        //       itemIndex = (itemIndex + 1) % this.items.length
        //     }, 600)
        // },

        methods: {

            // updateItemValue(index) {
            //   this.items[index].value = !this.items[index].value
            // },

            getResults(page = 1) {
              axios.get('api/local_list?page=' + page)
                .then(response => {
                  this.local_lists = response.data;
                });
            },

            updateLocalList() {
              this.$Progress.start();
              this.form.put('api/local_list/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Lokalna lista uspešno izmenjena!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(local_list) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(local_list);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteLocalList(id) {
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
                  this.form.delete('api/local_list/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Lokalna lista uspešno obrisana!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadLocalLists() {
              axios.get('api/local_list').then(({ data }) => (this.local_lists = data));
            },

            loadTowns() {
              axios.get('api/town').then(response => { this.towns = response.data.data; });
            },

            createLocalList() {
              this.$Progress.start();

              this.form.post('api/local_list')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Lokalna lista uspešno kreirana!'
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
            this.loadLocalLists();
            Fire.$on('AfterIsDone', () => {
              this.loadLocalLists();
            });
        }
    }
</script>
