<template>
  <form @submit.prevent="editmode ? updatePresidentialResult() : createPresidentialResult()">
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
                    <div class="card-header">Predsednička lista</div>
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
                        v-for="presidential_list in all_presidential_lists" 
                        v-bind:key="presidential_list.id" 
                        :value="presidential_list.id">
                        {{ presidential_list.name }}
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
              all_presidential_lists: {},
              all_electoral_units: {},
              presidential_results: {},
              form: new Form({
                id: '',
                electoral_units_id: '',
                presidential_results_id: ''
              })
            }
        },

        methods: {

            updatePresidentialResults() {
              this.$Progress.start();
              this.form.put('api/presidential_result/'+this.form.id)
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

            editModal(presidential_result) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(presidential_result);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deletePresidentialResults(id) {
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
                  this.form.delete('api/presidential_result/'+id).then(() => {
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

            loadPresidentialLists() {
              axios.get('api/all_presidential_lists').then(({ data }) => (this.all_presidential_lists = data));
            },

            loadElectoralUnits() {
              axios.get('api/all_electoral_units').then(({ data }) => (this.all_electoral_units = data));
            },

            loadPresidentialResults() {
              axios.get('api/presidential_result').then(response => { this.presidential_results = response.data.data; });
            },

            createPresidentialResult() {
              this.$Progress.start();

              this.form.post('api/presidential_result')
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
            this.loadPresidentialLists();
            this.loadElectoralUnits();
            this.loadPresidentialResults();
            Fire.$on('AfterIsDone', () => {
              this.loadPresidentialResults();
            });
        }
    }
</script>
