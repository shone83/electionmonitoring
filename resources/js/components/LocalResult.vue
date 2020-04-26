<template>
  <form @submit.prevent="editmode ? updateLocalResult() : createLocalResult()">
    <div class="form-row">
        <div class="col-4">
            <div class="form-group">
              <select multiple class="form-control" size="50">
                <option v-for="electoral_unit in all_electoral_units" 
                  v-bind:key="electoral_unit.id" 
                  :value="electoral_unit.id">
                    {{ electoral_unit.name }}
                </option>
              </select>
            </div>
        </div>
        <div class="col-8">
            <div class="form-row">
                <div class="card mb-8">
                    <div class="card-header">Lokalna lista</div>
                    <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ukupno listića
                        <span class="badge badge-primary badge-pill"><input type="text"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        Važeći
                        <span class="badge badge-primary badge-pill"><input type="text"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        Nevažeći
                        <span class="badge badge-primary badge-pill"><input type="text"></span>
                        </li>
                        <hr>
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                        v-for="local_list in all_local_lists" 
                        v-bind:key="local_list.id" 
                        :value="local_list.id">
                        {{ local_list.name }}
                        <span class="badge badge-primary badge-pill">
                            <input type="text">
                        </span>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </form>
</template>

<script>
    export default {
        data() {
            return {
              editmode: false,
              all_local_lists: {},
              all_electoral_units: {},
              local_results: {},
              form: new Form({
                id: '',
                electoral_units_id: '',
                local_lists_id: ''
              })
            }
        },

        methods: {

            updateLocalResults() {
              this.$Progress.start();
              this.form.put('api/local_result/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Rezultat uspešno izmenjen!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(local_result) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(local_result);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteLocalResults(id) {
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
                  this.form.delete('api/local_result/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Rezultat uspešno obrisan!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadLocalLists() {
              axios.get('api/all_local_lists').then(({ data }) => (this.all_local_lists = data));
            },

            loadElectoralUnits() {
              axios.get('api/all_electoral_units').then(({ data }) => (this.all_electoral_units = data));
            },

            loadLocalResults() {
              axios.get('api/local_result').then(response => { this.local_results = response.data.data; });
            },

            createLocalResult() {
              this.$Progress.start();

              this.form.post('api/local_result')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Rezultat uspešno unešen!'
                    })

                    this.$Progress.finish();
                })
              .catch(() => {
                  this.$Progress.fail();
              });

            }
        },

        created() {
            this.loadLocalLists();
            this.loadElectoralUnits();
            this.loadLocalResults();
            Fire.$on('AfterIsDone', () => {
              this.loadLocalResults();
            });
        }
    }
</script>
