  <!-- Begin Page Content -->
  <div class="container-fluid">

      <!-- Page Heading -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 justify-content-between row">
              <h3 class="m-0 font-weight-bold text-primary">Detail Ticket</h3>
              <a href="<?= base_url('ticket/print_ticket/' . $ticket['ID_TICKET']); ?>" class="btn btn-danger mb-3">Print Ticket</a>
          </div>
          <div class="row mt-3">
              <div class="col-lg-10">
                  <form action="<?= base_url('ticket/detail_ticket'); ?>">
                      <div class="form-group row">
                          <label for="user_complain" class="col-sm-3 control-label">ID Ticket</label>
                          <?= $id['ID_TICKET'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="user_complain" class="col-sm-3 control-label">User Complain</label>
                          <?= $id['USER_COMPLAIN'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="contact" class="col-sm-3 control-label">Contact</label>
                          <?= $id['CONTACT'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="divisi" class="col-sm-3 control-label">Divisi</label>
                          <?= $ticket['DIVISI'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="place" class="col-sm-3 control-label">Place</label>
                          <?= $ticket['PLACE'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="category" class="col-sm-3 control-label">Category</label>
                          <?= $ticket['CATEGORY'] ?>
                      </div>
                      <div class="form-floating row mb-3">
                          <label for="detail" class="col-sm-3 control-label">Detail</label>
                          <?= $ticket['DETAIL'] ?>
                      </div>
                      <!-- <div class="form-check row">
                <label for="solve" class="col-sm-3 control-label">Solve</label>
                    <input class="form-check-solve" type="checkbox" value="3" id="solve">
                    <label class="form-check-label" for="solve">
                        Can Solve Alone ?
                    </label>
                </div> -->
                      <div class="form-group row">
                          <label for="id_technician" class="col-sm-3 control-label">Technician</label>
                          <?= $ticket['TECHNICIAN_NAME'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="id_status" class="col-sm-3 control-label">Status</label>
                          <?= $ticket['STATUS'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="how_to_solve" class="col-sm-3 control-label">How To Solve</label>
                          <?= $ticket['HOW_TO_SOLVE'] ?>
                      </div>
                      <div class="form-group row">
                          <label for="note" class="col-sm-3 control-label">Note</label>
                          <?= $ticket['NOTE'] ?>
                      </div>