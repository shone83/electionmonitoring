<template>
  <form @submit.prevent="editmode ? updateTurnout() : createTurnout()">
    <div class="form-row">
        <div class="col">
            <div class="form-group">
              <select multiple class="form-control" size="50">
                <option v-for="electoral_unit in electoral_units" 
                  v-bind:key="electoral_unit.id" 
                  :value="electoral_unit.id">
                    {{ electoral_unit.name }}
                </option>
              </select>
            </div>
        </div>
        <div class="col">
          <div class="form-row">
            <div class="col-4">
              <select name="" class="form-control">
                <option value="08:00">08:00</option>
                <option value="09:00">09:00</option>
                <option value="10:00">10:00</option>
                <option value="11:00">11:00</option>
                <option value="12:00">12:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
                <option value="17:00">17:00</option>
                <option value="18:00">18:00</option>
                <option value="19:00">19:00</option>
                <option value="20:00">20:00</option>
              </select>
            </div>
            <div class="form-group col-4">
              <input type="text" name="" class="form-control" placeholder="Izašlo birača">
            </div>
            <div class="form-group col-4">
              <input type="text" name="" class="form-control" placeholder="Izašlo kapilarnih">
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="float-left">
                <button type="submit" class="btn btn-primary">Uneti <i class="fas fa-plus"></i></button>
              </div>
            </div>
            <div class="col">
              <div class="float-right">
                <button type="button" class="btn btn-success">Excel <i class="fas fa-file-excel"></i></button>
                <button type="button" class="btn btn-dark">Štampanje <i class="fas fa-print"></i></button>
              </div>
            </div>
          </div>
          <br> <br>
          <div class="row">
            <div class="col">
              <h5>Izborna jedinica - </h5>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Vreme</th>
                    <th scope="col">Birači</th>
                    <th scope="col">Kapilarni</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>08:00</td>
                    <td>7</td>
                    <td>2</td>
                  </tr>
                  <tr>
                    <td>09:00</td>
                    <td>22</td>
                    <td>6</td>
                  </tr>
                  <tr>
                    <td>10:00</td>
                    <td>48</td>
                    <td>17</td>
                  </tr>
                </tbody>
              </table>
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
              electoral_units: {},
              turnouts: {},
              form: new Form({
                id: '',
                electoral_units_id: '',
                turnout: '',
                safe_voter: '',
                turnout_by_hour: ''
              })
            }
        },

        methods: {

            updateTurnout() {
              this.$Progress.start();
              this.form.put('api/turnout/'+this.form.id)
              .then(() => {
                $('#addNew').modal('hide');
                Toast.fire({
                      icon: 'success',
                      title: 'Izlaznost uspešno izmenjena!'
                    })
                this.$Progress.finish();
                Fire.$emit('AfterIsDone');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },

            editModal(turnout) {
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(turnout);
            },

            newModal() {
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },

            deleteTurnout(id) {
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
                  this.form.delete('api/turnout/'+id).then(() => {
                      Toast.fire({
                      icon: 'success',
                      title: 'Izlaznost uspešno obrisana!'
                    })
                  Fire.$emit('AfterIsDone');
                  }).catch(() => {
                    Swal("Nije uspelo!", "Nešto nije u redu!", "warning");
                  });
                }
              })
            },

            loadElectoralUnits() {
              axios.get('api/electoral_unit').then(response => { this.electoral_units = response.data.data; });
            },

            loadTurnouts() {
              axios.get('api/turnout').then(response => { this.turnouts = response.data.data; });
            },

            createTurnout() {
              this.$Progress.start();

              this.form.post('api/turnout')
              .then(() => {
                    Fire.$emit('AfterIsDone');
                    $('#addNew').modal('hide')

                    Toast.fire({
                      icon: 'success',
                      title: 'Izlaznost uspešno unesena!'
                    })

                    this.$Progress.finish();
                })
              .catch(() => {
                  this.$Progress.fail();
              });

            }
        },

        created() {
            this.loadElectoralUnits();
            this.loadTurnouts();
            Fire.$on('AfterIsDone', () => {
              this.loadTurnouts();
            });
        }
    }
</script>
