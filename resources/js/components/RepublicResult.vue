<template>
  <form @submit.prevent="editmode ? updateRepublicResult() : createRepublicResult()">
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
                    <div class="card-header">Republička lista</div>
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
                        v-for="republic_list in all_republic_lists" 
                        v-bind:key="republic_list.id" 
                        :value="republic_list.id">
                        {{ republic_list.name }}
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
              all_republic_lists: {},
              all_electoral_units: {},
              republic_results: {},
              form: new Form({
                id: '',
                electoral_units_id: '',
                republic_results_id: ''
              })
            }
        },

        methods: {

            updateRepublicResults() {
              this.$Progress.start();
              this.form.put('api/republic_result/'+this.form.id)
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

            editModal(republic_result) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(republic_result);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteRepublicResults(id) {
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
                  this.form.delete('api/republic_result/'+id).then(() => {
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

            loadRepublicLists() {
              axios.get('api/all_republic_lists').then(({ data }) => (this.all_republic_lists = data));
            },

            loadElectoralUnits() {
              axios.get('api/all_electoral_units').then(({ data }) => (this.all_electoral_units = data));
            },

            loadRepublicResults() {
              axios.get('api/republic_result').then(response => { this.republic_results = response.data.data; });
            },

            createRepublicResult() {
              this.$Progress.start();

              this.form.post('api/republic_result')
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
            this.loadRepublicLists();
            this.loadElectoralUnits();
            this.loadRepublicResults();
            Fire.$on('AfterIsDone', () => {
              this.loadRepublicResults();
            });
        }
    }
</script>
